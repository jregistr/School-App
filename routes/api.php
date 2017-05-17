<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/overview/summary', 'API\OverviewController@summary');


Route::get('/overview/weight', 'API\OverviewController@getWeight');
Route::post('/overview/weight', 'API\OverviewController@addWeight');
Route::put('/overview/weight', 'API\OverviewController@updateWeight');
Route::delete('/overview/weight', 'API\OverviewController@deleteWeight');


Route::get('/overview/grade', 'API\OverviewController@getGrade');
Route::post('/overview/grade', 'API\OverviewController@addGrade');
Route::put('/overview/grade', 'API\OverviewController@updateGrade');
Route::delete('/overview/grade', 'API\OverviewController@deleteGrade');

Route::get('/schedule', 'API\ScheduleController@getUserSchedules');
Route::post('/schedule', 'API\ScheduleController@updateSchedule');
Route::delete('/schedule', 'API\ScheduleController@deleteSchedule');


Route::get('/schedule/course', 'API\ScheduleController@getCourse');////todo Implement this endpoint
Route::post('/schedule/course', 'API\ScheduleController@addCourse');


Route::get('/course', 'API\CourseController@getCourses');
Route::post('/course', 'API\CourseController@createCourse');////todo implement this endpoint

Route::get('/course/section', 'API\CourseController@getSections'); ///todo implement
Route::post('/course/section', 'API\CourseController@createSection'); ///todo implement

