<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sieve</title>
  <link rel="stylesheet" href="/css/app.css">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <link rel="stylesheet" href="/assets/css/style.css">

  <script src="https://use.fontawesome.com/c77166cce3.js"></script>

</head>

<body>
  <div id="app">
    <div class="container">
      <div class="content">
        <div class="title m-b-md">
          Sieve
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <form class="new_filter" id="new_filter" action="/mail_filters" accept-charset="UTF-8" method="post">
                <input name="utf8" type="hidden" value="✓"> {{csrf_field()}}
                <input type="hidden" name="authenticity_token" value="q0/dUMBDDosL3dpJMEbA/YC8snyILVjD46I+klfaLvDctGJRDu1MEbYyIPlVkJ9b9iAO/OeT8hE6sXUmlX/B0g==">
                <div class="form-group">
                  <label for="filter_name">Filter Name</label>
                  <input class="form-control" type="text" v-model="filter.name" name="filter[name]" id="filter_name">
                </div>
                <div class="form-group">
                  <label for="filter_name">Conditions</label>
                  <table class="expressions">
                    <tbody>
                      <tr>
                        <td>
                          <select name="filter[boolean_operator]" v-model="filter.boolean_operator" id="filter_boolean_operator" class="form-control">
                            <option value="allof">All Of</option>
                            <option selected="selected" value="anyof">Any Of</option>
                          </select>
                        </td>
                      </tr>
                      <tr v-for="(ex, index) in expressions">
                        <td>
                          <select :name="'expression[' + index + '][field_name]'" v-model="ex.field_name" id="expression_1_field_name" class="form-control">
                            <option selected="selected" value=""></option>
                            <option value="From">From</option>
                            <option value="To">To</option>
                            <option value="CC">CC</option>
                            <option value="Subject">Subject</option>
                            <option value="Body">Body</option>
                            <option value="List-Id">List-Id</option>
                          </select>
                        </td>
                        <td>
                          <select :name="'expression[' + index + '][operator]'" id="expression_1_operator" v-model="ex.operator" class="form-control">
                            <option value="contains">Contains</option>
                            <option value="starts">Starts With</option>
                            <option value="ends">Ends With</option>
                            <option value="exactly">Is Exactly</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" :name="'expression[' + index + '][expr_value]'" id="expression_1_expr_value" v-model="ex.expr_value" class="form-control">
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="form-group">
                  <label for="filter_destination_folder">Destination Folder</label>
                  <input class="form-control" type="text" v-model="filter.destination_folder" name="filter[destination_folder]" id="filter_destination_folder">
                  <span class="help-block">The folder name is case sensitive. To delete the message, send to the "Trash" folder.
                  </span>
                </div>
                <div class="form-group">
                  <label for="filter_email">Save for this email / or get filter from this email</label>
                  <input class="form-control" type="text" v-model="filter.email" name="filter[email]" id="filter_email">
                  <span class="help-block">If you want to edit some rules, enter email and click get Code
                  </span>
                </div>
                <div class="btn-toolbar">
                  <button class="btn btn-default" type="reset">
                    <span class="glyphicon glyphicon-remove"></span> Cancel
                  </button>
                  <input name="utf8" type="hidden" value="✓">
                  <input type="hidden" name="authenticity_token" value="AeYF1xWfwDvEs/UwO2DU97WBBA2o8Pk4L4AtMWfMvh52HbrW2zGCoXlcD4BetotRwx24jcdOU+r2k2aFpWlRPA==">
                  <button class="btn btn-info" type="button" @click="validate">
                    <span class="glyphicon glyphicon-ok"></span> Show code
                  </button>
                  <button class="btn btn-success" type="button" @click="saveFilter">
                    <span class="glyphicon glyphicon-ok"></span> Save / Overwrite filter
                  </button>
                  <button class="btn btn-success" type="button" @click="getFilter">
                    <span class="glyphicon glyphicon-ok"></span> Get filter Code / Edit
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container" v-if="code !== ''">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2>Generated Code</h2>
        </div>
        <div class="col-md-12">
          <div class="jumbotron">
            <pre v-html="code"></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://vuejs.org/js/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.17.1/axios.min.js"></script>
  <script src="/assets/js/sieve.js"></script>
</body>

</html>