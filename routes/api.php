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
Route::middleware('auth:api')->put('user-department/{id}', 'UserController@change_department');
Route::middleware('auth:api')->get('users', 'UserController@index');
Route::middleware('auth:api')->put('user/{id}', 'UserController@change_detail');
Route::middleware('auth:api')->get('user-by-role', 'UserController@get_user_by_role');
Route::post('login', 'Auth\LoginController@login');

//Relation route
Route::middleware('auth:api')->post('relation', 'RelationController@store');
Route::middleware('auth:api')->get('relations', 'RelationController@index');