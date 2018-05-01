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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Department route
Route::get('departments', 'DepartmentController@index');
Route::get('departments/{id}', 'DepartmentController@show');
Route::post('departments', 'DepartmentController@store');

//User route
Route::middleware('auth:api')->post('register', 'UserController@create');
Route::get('users', 'UserController@index');
Route::post('login', 'Auth\LoginController@login');
