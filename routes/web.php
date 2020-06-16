<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| start script: php -S localhost:8000 -t public
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version() . "hey there!";
});

//testing with named route variables --> it's dope
$router->get('/test/{variable}', function ($variable) use ($router) {
    return $variable;
});

//an example where we use the 'Example' middleware, defined in bootstrap/app.php
$router->group(['middleware' =>'example'], function() use ($router) {
    $router->get('/example', function() {
        return "Example!!!!";
    });

});
//an example where the middleware is passed a parameter, which shows up in its function after next
$router->group(['middleware' =>'example-with-param:value2'], function() use ($router) {
    $router->get('/example-with-param', function() {
        return "Example lol";
    });

});

//basic use of controller routing *note that the 
$router->get('/bruh', ['uses' => 'BruhController@index']);

$router->get('/request', function(Illuminate\Http\Request $requestObj) use ($router) {
    $all = $requestObj->all();
    return print_r($all, true);
});

$router->get('/db-query', function (Illuminate\Http\Request $requestObj) use ($router) {
    return DB::select('SELECT * FROM users');
});

$router->get('/model-user', function(Illuminate\Http\Request $requestObj) use ($router) {
    $users = App\User::all();
    return $users;
});

$router->get('/test', function(Illuminate\Http\Request $requestObj) use ($router) {
    return ['lol' => 'lol'];
});

$router->post('/user/login', ['uses' => 'UserController@login']);

$router->group(['middleware' =>'auth'], function() use ($router) {
    $router->get('/user/current', ['uses' => 'UserController@getCurrent']);
    $router->put('/user', ['uses' => 'UserController@update']);



    $router->post('/location', ['uses' => 'LocationController@create']);
    $router->delete('/location/{locationId}', ['uses' => 'LocationController@delete']);
    $router->get('/location', ['uses' => 'LocationController@getAll']);

    $router->post('/product', ['uses' => 'ProductController@create']);
    $router->delete('/product/{productId}', ['uses' => 'ProductController@delete']);
    $router->get('/product', ['uses' => 'ProductController@getAll']);

    $router->post('/shop-list', ['uses' => 'ShopListController@create']);
    $router->delete('/shop-list/{shopListId}', ['uses' => 'ShopListController@delete']);
    $router->get('/shop-list', ['uses' => 'ShopListController@getAll']);

});