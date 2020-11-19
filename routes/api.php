<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("lecturer/login", 'LecturerController@login');
Route::post("student/login", 'StudentController@login');
Route::apiResource('activities', 'ActivitieController');
Route::apiResource('announcement', 'AnnouncementController');
Route::apiResource('forums', 'ForumController');
Route::apiResource('lecturer', 'LecturerController');
Route::apiResource('roles', 'RoleController');
Route::apiResource('student', 'StudentController');
Route::apiResource('years', 'YearController');

