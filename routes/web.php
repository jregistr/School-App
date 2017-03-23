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

Route::get('/user/activation/{confirm_token}', 'Auth\ActivationController@activateUser')->name('user.activate');



//Route::get('/send', 'Auth\ActivationController@send');

//Route::get('/test1', 'HomeController@doCoolStuff');
//
//Route::get('/test2', function () {
//    return view('alsocool');
//});
