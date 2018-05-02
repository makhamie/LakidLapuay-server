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
// require auth route

Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => ['auth:api']], function() {
<<<<<<< HEAD

  // User routes
  Route::post('/register', 'UserController@create');
  Route::put('user-department/{id}', 'UserController@change_department');
  Route::get('users', 'UserController@index');
  Route::put('user/{id}', 'UserController@change_detail');
  Route::get('user-by-role', 'UserController@get_user_by_role');
  Route::get('user', 'UserController@get_user');

  // Department routes
  Route::get('departments', 'DepartmentController@index');
  Route::get('departments/{id}', 'DepartmentController@show');
  Route::post('departments', 'DepartmentController@store');

  // Relation routes
  Route::post('relation', 'RelationController@store');
  Route::get('relations', 'RelationController@index');
});
=======

  // User routes
  Route::post('/register', 'UserController@create');
  Route::put('user-department/{id}', 'UserController@change_department');
  Route::get('users', 'UserController@index');
  Route::put('user/{id}', 'UserController@change_detail');
  Route::get('user-by-role', 'UserController@get_user_by_role');
  Route::get('user', 'UserController@get_user');

  // Department routes
  Route::get('departments', 'DepartmentController@index');
  Route::get('departments/{id}', 'DepartmentController@show');
  Route::post('departments', 'DepartmentController@store');

  // Relation routes
  Route::post('relation', 'RelationController@store');
  Route::get('relations', 'RelationController@index');
});
//Department route
// Route::get('departments', 'DepartmentController@index');
// Route::get('departments/{id}', 'DepartmentController@show');
// Route::post('departments', 'DepartmentController@store');

//User route
// Route::middleware('auth:api')->post('register', 'UserController@create');
// Route::middleware('auth:api')->put('user-department/{id}', 'UserController@change_department');
// Route::middleware('auth:api')->get('users', 'UserController@index');
// Route::middleware('auth:api')->put('user/{id}', 'UserController@change_detail');
// Route::middleware('auth:api')->get('user-by-role', 'UserController@get_user_by_role');
// Route::middleware('auth:api')->get('user', 'UserController@get_user');
// Route::post('login', 'Auth\LoginController@login');

//Relation route
// Route::middleware('auth:api')->post('relation', 'RelationController@store');
// Route::middleware('auth:api')->get('relations', 'RelationController@index');
>>>>>>> 67523b67387266bcf25b25dd24cba65ff17718f1
