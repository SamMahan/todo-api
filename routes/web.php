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

$router->post('/user/login', ['uses' => 'UserController@login']);
$router->post('/user', ['uses' => 'UserController@create']);

// $router->get('/todo', function () use ($router) {
//     return $router->app->version() . "hey there!";
// });

$router->group(['middleware' =>'auth'], function() use ($router) {
    
    $router->get('/user/current', ['uses' => 'UserController@getCurrent']);
    $router->put('/user', ['uses' => 'UserController@update']);
    
    $router->get('/', function () use ($router) {
        return $router->app->version() . "hey there!";
    });

    $router->post('/todo', ['uses' => 'ToDoController@create']);
    $router->delete('/todo/{todoId}', ['uses' => 'ToDoController@delete']);
    $router->get('/todo', ['uses' => 'ToDoController@get']);
    $router->put('/todo', ['uses' => 'ToDoController@update']);
});