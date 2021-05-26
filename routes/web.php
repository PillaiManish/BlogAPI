<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('register','AccountController@register');
$router->post('login','AccountController@login');

$router->group(['prefix' => 'blog'], function () use ($router) {
    $router->get('/','BlogController@list');
    $router->post('add','BlogController@add');
    $router->patch('edit','BlogController@edit');
    $router->delete('delete','BlogController@delete');

});
