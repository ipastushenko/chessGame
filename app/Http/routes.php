<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Root
Route::get('/', function () {
    return view('welcome');
});

// Authentiction
Route::auth();
Route::get('/user/confirmation/{token}', 'Auth\AuthController@confirmation');

// Dashboard
Route::get('/home', 'HomeController@index');
