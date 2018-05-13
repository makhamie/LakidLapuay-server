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
Route::post('/register', 'UserController@create');
Route::post('/departments', 'DepartmentController@store');
  
Route::group(['middleware' => ['auth:api']], function() {
  // User routes
  // Route::post('/register', 'UserController@create');
  Route::get('/admin/users', 'UserController@index');
  Route::put('/admin/user/{id}', 'UserController@admin_manage_user');
  Route::get('/admin/user-by-role', 'UserController@admin_get_user_by_role');
  Route::get('/admin/user', 'UserController@admin_get_user');
  Route::put('/user-update', 'UserController@user_update_profile');
//   Route::get('', '')


  // Department routes
  Route::get('/departments', 'DepartmentController@index');
  Route::get('/departments/{id}', 'DepartmentController@show');

  // Relation routes
  Route::post('/admin/relation', 'RelationController@store');
  Route::get('/admin/relations', 'RelationController@index');
  Route::get('/supervisor-relations/subordinates', 'RelationController@get_subordinates'); 
  Route::get('/subordinate-relations/collations', 'RelationController@get_collations');

  // Tasks routes
  Route::get('/tasks', 'TaskController@index');
  Route::get('/tasks/{id}', 'TaskController@show');
  Route::get('/tasks/supervisor-tasks', 'TaskController@get_supervisor_task');
  // Route::post('/task', 'TaskController@store');
  
  // LeaveRequest routes
  // Route::post('/leave-request', 'LeaveRequestController@store');
  // Route::put('/leave-requests/substitute-approve', 'LeaveRequestController@substitute_approve');
  // Route::put('/leave-requests/supervisor-approve', 'LeaveRequestController@supervisor_approve');
  Route::get('/leave-requests/get-leave-request', 'LeaveRequestController@get_leave_requests');

});
