<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/dashboard', 'HomeController@dashboard');

Route::get('/profile', 'HomeController@editProfile');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/overview', function ()    {
        return view('overview');
    });
});


Route::get('/user/activation/{confirm_token}', 'Auth\ActivationController@activateUser')->name('user.activate');
