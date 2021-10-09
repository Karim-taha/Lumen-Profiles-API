<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// use App\Http\Controllers\UserController;


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

// Using JWT and uses Bearer token.

// users routes
$router->post('/login', 'UserController@login');
$router->post('/register', 'UserController@register');


$router->group(['middleware' => 'auth'], function () use ($router){
    $router->post('/logout', 'UserController@logout');
    // articles routes
    $router->get('/articles', 'ArticleController@index');
    $router->post('/articles', 'ArticleController@store');
    $router->put('/article/{id}', 'ArticleController@update');
    $router->delete('/article/{id}', 'ArticleController@destroy');
});

$router->group(['middleware' => 'auth'], function () use ($router){
$router->get('/email/resend', 'VerificationController@resend');
$router->get('/email/verify/{id}/{hash}', 'VerificationController@verify');
});

