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
$router->get('/restaurant/{id}', 'RestaurantController@index');
$router->post('/restaurant', 'RestaurantController@store');
$router->put('/restaurant/{id}', 'RestaurantController@update');
$router->delete('/restaurant/{id}', 'RestaurantController@delete');

//MIDDLEWARE
$router->post('/auth/login', ['prefix' => 'api', 'uses' =>  'AuthController@login']);
$router->post('/auth/user-check', ['prefix' => 'api', 'uses' =>  'AuthController@me']);
$router->post('/auth/register', ['prefix' => 'api', 'uses' =>  'AuthController@register']);
$router->post('/auth/logout', ['prefix' => 'api', 'uses' =>  'AuthController@logout']);


//FIRESTORE
$router->post('/company', 'CompanyController@store');
$router->put('/company/{id}', 'CompanyController@update');
$router->delete('/company/{id}', 'CompanyController@delete');
$router->get('/company/{id}', 'CompanyController@getCompany');

//FILTERING
$router->get('/filter', 'FilterController@index');

//SENTRY TEST

$router->get('debug-sentry', function () use ($router) {
    return throw new Exception('My first Sentry error!');
});

// External API

$router->post('/external/register', 'ExternalController@register');
$router->post('/external/login', 'ExternalController@login');