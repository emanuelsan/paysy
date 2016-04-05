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

Route::get('/', 'CustomerController@welcome');
Route::post('/addCustomer', ['as'=>'addCustomer' , 'uses'=>'CustomerController@addCustomer']);

$this->get('emanuelsan', 'Auth\AuthController@showLoginForm');
$this->post('emanuelsan', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');

Route::get('/select', ['middleware' => 'auth', 'as'=>'select', 'uses'=>'PaymentController@select']);
Route::post('/selectcard', ['middleware' => 'auth', 'as'=>'selectcard', 'uses'=>'PaymentController@selectcard']);
Route::post('/confirm', ['middleware' => 'auth', 'as'=>'confirm', 'uses'=>'PaymentController@confirm']);
Route::post('/withdraw', ['middleware' => 'auth', 'as'=>'withdraw', 'uses'=>'PaymentController@withdraw']);
