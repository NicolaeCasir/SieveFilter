<?php

namespace Nicolae\Sieve\Http;

use Illuminate\Http\Request;
use File;
use Input;

class FilterController
{
		public function getFilter () {
			$email = explode("@", Input::get('email'));
    	$username = $email[0];
    	$domain = $email[1];

    	if(@file_get_contents(resource_path('/sieve/' . $domain . '/' . $username. '/sieve/rules.sieve')) === false){
    		return "404";
    	}
    	$script = file_get_contents(resource_path('/sieve/' . $domain . '/' . $username. '/sieve/rules.sieve'));
    	$exploded = explode("\n", $script);
    	$expressions = array();
    	$filter = [
    		"name" => "rules",
    		"boolean_operator" => "",
    		"destination_folder" => "",
    		"email" => Input::get('email')
    	];
    	$count = 0;

    	foreach($exploded as $i => $line) {
    		if(str_contains($line, "allof")){
    			$filter["boolean_operator"] = "allof";
    			$start = $i+1;
    		}
    		if(str_contains($line, "anyof")){
    			$filter["boolean_operator"] = "anyof";
    			$start = $i+1;
    		}
    		if(str_contains($line, ")")){
	    		$end = $i;
	    	}
    			
    		if(str_contains($line, 'fileinto')) {
    			$str = explode("\"", $line);
    			$filter["destination_folder"] = $str[1];
    		}
    	}

    	foreach($exploded as $i => $line) {
    		if($i >= $start && $i < $end){
    			//if contains
    			if(str_contains($line, ":contains")){
	    			$expressions[$count]['operator'] = "contains";

	    			$str = explode("\"", $line);

	    			$expressions[$count]['field_name'] = $str[1];
	    			$expressions[$count]['expr_value'] = $str[3];
	    			$count++;
	    		}

	    		//if start with or end with
    			if(str_contains($line, ":regex")){

	    			$str = explode("\"", $line);

	    			$value = str_split($str[3]);
	    			if ($value[0] === '^') {
	    					$expressions[$count]['expr_value'] = ltrim($str[3], '^');
	    					$operator = "starts";
	    			} else {
	    				$expressions[$count]['expr_value'] = substr($str[3], 0, -1);
	    				$operator = "ends";
	    			}
	    			$expressions[$count]['field_name'] = $str[1];
	    			

	    			$expressions[$count]['operator'] = $operator;
	    			$count++;
	    		}

	    		//if is exactly
    			if(str_contains($line, ":is")){

	    			$str = explode("\"", $line);

	    			$expressions[$count]['operator'] = "exactly";
	    			$expressions[$count]['field_name'] = $str[1];
	    			$expressions[$count]['expr_value'] = $str[3];
	    			$count++;
	    		}

    		}
    	}

    	return collect([
    		"filter" => $filter,
    		"expressions" => $expressions
    	]);
    	

		}
    public function saveFilter (Request $request){
    	$script = $this->validateScript($request);

    	$email = explode("@", $request->params['filter']['email']);
    	$username = $email[0];
    	$domain = $email[1];

    	$path = resource_path('sieve/' . $domain . '/' . $username. '/sieve/');
    	File::makeDirectory($path, 0777, true, true);
    	$return = File::put($path . 'rules.sieve', $script);
    	return "Sieve script was saved with name rules.sieve in " . $path;

    }

    public function validateScript (Request $request){
    	$r = $request->params;
    	$t = 0;

    	foreach($r['expressions'] as $ex){
			if($ex['field_name'] !== null) $t++;
		}

$script = 'require ["fileinto", "envelope", "body", "regex"];
';

    	if($r['filter']['boolean_operator'] === "anyof"){
$script .= '	if anyof
';
    	} else {
$script .= '	if allof
';
    	}
$script .= '		(
';
		foreach($r['expressions'] as $i => $ex){
			if($ex['field_name'] !== null){
				switch ($ex['operator']) {
					case 'contains':
$script .= '			envelope :' . $ex['operator'] . ' "' . $ex['field_name'] . '" "' . $ex['expr_value'] . '"';
						if($i !== $t-1){
$script .= ',
';
						} else {
$script .= '
';
						}
						break;
					case 'ends':
$script .= '			envelope :regex "' . $ex['field_name'] . '" "' . $ex['expr_value'] . '$"';
						if($i !== $t-1){
$script .= ',
';
						} else {
$script .= '
';
						}
						break;
					case 'starts':
$script .= '			envelope :regex "' . $ex['field_name'] . '" "^' . $ex['expr_value'] . '"';
						if($i !== $t-1){
$script .= ',
';
						} else {
$script .= '
';
						}
						break;
					case 'exactly':
$script .= '			envelope :is "' . $ex['field_name'] . '" "' . $ex['expr_value'] . '"';
						if($i !== $t-1){
$script .= ',
';
						} else {
$script .= '
';
						}
						break;
					default:
						break;
				}
			}
		}
$script .= '		)
';
$script .= '	{
	';
$script .= '	fileinto "'.$r['filter']['destination_folder'].'";
';
$script .= '	}
';
 
    	return $script;
    }
}