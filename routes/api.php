<?php

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
Route::put('/schedule', 'API\ScheduleController@createSchedule');
Route::delete('/schedule', 'API\ScheduleController@deleteSchedule');

Route::get('/schedule/course', 'API\ScheduleController@getScheduledCourses');
Route::put('/schedule/course', 'API\ScheduleController@addScheduledCourse'); //todo - working on this
Route::post('/schedule/course', 'API\ScheduleController@editScheduledCourse');
Route::delete('/schedule/course', 'API\ScheduleController@deleteScheduledCourse');//todo

Route::get('/schedule/generator', 'API\ScheduleGeneratorController@getGenerator');
Route::put('/schedule/generator', 'API\ScheduleGeneratorController@addToGenerator');
Route::post('/schedule/generator', 'API\ScheduleGeneratorController@modifyGeneratorEntry');
Route::delete('/schedule/generator', 'API\ScheduleGeneratorController@deleteOnGenerator');

Route::post('/schedule/generator/generate', 'API\ScheduleGeneratorController@generateSchedules');///todo

Route::get('/course', 'API\CourseController@getCourses');
Route::post('/course', 'API\CourseController@createCourse');

Route::get('/course/section', 'API\CourseController@getSections');
Route::post('/course/section', 'API\CourseController@createSection');


