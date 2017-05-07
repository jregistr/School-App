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

Route::get('/profile', 'HomeController@editProfile');

Route::get('/schedules', 'HomeController@schedules');

Route::get('/overview', 'HomeController@overview');

Route::get('/create', 'HomeController@create');


Route::get('/user/activation/{confirm_token}', 'Auth\ActivationController@activateUser')->name('user.activate');

Route::get('/addclass', 'HomeController@addclass');

Route::get('/selectclass', 'HomeController@selectclass');

Route::resource('classes', 'Auth\ClassController');