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

Route::post("lecturers/login", 'LecturerController@login');
Route::post("students/login", 'StudentController@login');
Route::apiResource('activities', 'ActivitieController');
Route::apiResource('announcements', 'AnnouncementController');
Route::apiResource('forums', 'ForumController');
Route::apiResource('lecturers', 'LecturerController');
Route::apiResource('roles', 'RoleController');
Route::apiResource('students', 'StudentController');
Route::apiResource('years', 'YearController');
Route::apiResource('cts', 'CtController');
Route::apiResource('soalPgs', 'SoalPgController');
Route::apiResource('soalIsians', 'SoalIsianController');

