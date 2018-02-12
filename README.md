# SieveFilter
Generate sieve code from HTML form, and parse local sieve code for editing

# Installation
Run:

`composer require nicolaecasir/sieve`

`php artisan vendor:publish` and select `Provider: Nicolae\Sieve\SieveServiceProvider`

add this line `'Input' => Illuminate\Support\Facades\Input::class,` to your `config/app.php` in aliases

`php artisan serve`

Go to `http://localhost:8000/sieve`

### Thanks ;)
