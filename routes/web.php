<?php

Route::get('/sieve', function () {
	return view('sieve::home');
});
Route::post('/api/validate', 'Nicolae\Sieve\Http\FilterController@validateScript');
Route::post('/api/saveFilter', 'Nicolae\Sieve\Http\FilterController@saveFilter');
Route::get('/api/getFilter', 'Nicolae\Sieve\Http\FilterController@getFilter');