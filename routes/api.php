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

  // LeaveRequest routes
  Route::post('leave-request', 'LeaveRequestController@store');

});
