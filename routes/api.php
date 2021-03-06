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
Route::post("students/addPanitia", 'StudentController@addPanitia');
Route::get("students/getPanitia", 'StudentController@getPanitia');
Route::get("students/getMahasiswa", 'StudentController@getMahasiswa');
Route::get("comments/getAllCommentByIdForum/{id}", 'CommentController@getAllCommentByIdForum');
Route::get("soalIsians/getAllSoalIsianByIdCT/{id}/{userid}", 'SoalIsianController@getAllSoalIsianByIdCT');
Route::get("soalPgs/getAllSoalPgsByIdCT/{id}/{userId?}", 'SoalPgController@getAllSoalPgByIdCts');
Route::get("cts/getJawabanPerMahasiswaPerCT/{id}/{userId?}", 'CtController@getJawabanPerMahasiswaPerCT');
Route::get("cts/getCtsByUser/{userid}", 'CtController@getCtsByUser');
Route::get("cts/getPengerjaanByCT/{ct}", 'CtController@getPengerjaanMhsByCT');
Route::get("soalPgs/storeAnswerPGGet/{jwb}", 'SoalPgController@storeAnswerPGGet');
Route::get("soalPgs/storeAnswerIsianGet/{jwb}", 'SoalPgController@storeAnswerIsianGet');
Route::get("soalPgs/getScorePG/{id}/{userid}", 'SoalPgController@getScorePG');
Route::apiResource('activities', 'ActivitieController');
Route::apiResource('announcements', 'AnnouncementController');
Route::apiResource('forums', 'ForumController');
Route::apiResource('lecturers', 'LecturerController');
Route::apiResource('roles', 'RoleController');
Route::apiResource('roleHasStudents', 'RoleHasStudentController');
Route::apiResource('students', 'StudentController');
Route::apiResource('years', 'YearController');
Route::apiResource('cts', 'CtController');
Route::apiResource('soalPgs', 'SoalPgController');
Route::apiResource('soalIsians', 'SoalIsianController');
Route::apiResource('comments', 'CommentController');

