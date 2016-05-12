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

Route::get('/', ['as'=> 'welcome', 'uses' => 'CustomerController@welcome']);
Route::get('/pay/{customer}', ['as' => 'customerPaymentPage', 'uses' => 'PaymentController@customerPaymentPage']);
Route::post('/pay/{customer}', ['as' => 'customerPayment', 'uses' => 'PaymentController@customerPayment']);

$this->get('emanuelsan', 'Auth\AuthController@showLoginForm');
$this->post('emanuelsan', 'Auth\AuthController@login');
$this->get('logout', 'Auth\AuthController@logout');

Route::group(['middleware' => 'auth','prefix' => 'admin'], function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'AdminController@dashboard']);
    
    Route::resource('rooms', 'RoomController');
    Route::resource('customers', 'AdminCustomerController');
    Route::resource('payments', 'AdminPaymentController');

});