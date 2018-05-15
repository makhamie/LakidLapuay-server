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
Route::post('/admin/department', 'DepartmentController@store');
  
Route::group(['middleware' => ['auth:api']], function() {
  // User routes
  // Route::post('/register', 'UserController@create');
  Route::get('/user-role', 'UserController@get_user_role');
  Route::get('/admin/users', 'UserController@index');
  Route::put('/admin/user/{id}', 'UserController@admin_manage_user');
  Route::get('/admin/user-by-role', 'UserController@admin_get_user_by_role');
  Route::get('/admin/get-available-supervisor', 'UserController@get_available_supervisor');
  Route::get('/get-user', 'UserController@admin_get_user');
  Route::put('/user-update', 'UserController@user_update_profile');
  
  // Department routes
  Route::get('/admin/departments', 'DepartmentController@index');
  Route::get('/admin/departments/{id}', 'DepartmentController@show');

  // Relation routes
  Route::post('/admin/relation', 'RelationController@store');
  Route::put('/admin/update-user-relation', 'RelationController@update_user_relation');
  Route::get('/admin/relations', 'RelationController@index');
  Route::get('/admin/relations/get-user-supervisor', 'RelationController@get_supervisor_by_id');
  Route::get('/get-subordinates', 'RelationController@get_subordinates'); 
  Route::get('/get-collations', 'RelationController@get_collations');

  // Tasks routes
  Route::get('/tasks', 'TaskController@index');
  Route::get('/tasks/get-supervisor-tasks', 'TaskController@get_supervisor_tasks');
  Route::get('/tasks/get-subordinate-tasks', 'TaskController@get_subordinate_tasks');
  Route::get('/tasks/get-tasks-in-range', 'TaskController@get_tasks_in_range');
  Route::get('/tasks/{id}', 'TaskController@show');
  Route::post('/task', 'TaskController@store');
  Route::post('/task/update_task', 'TaskController@update_task');
  
  // LeaveRequest routes
  Route::post('/leave-request', 'LeaveRequestController@store');
  //Supervisor response leave request
  Route::put('/response-leave-request', 'LeaveRequestController@response_leave_request');
  //Get all leave request of this supervisor
  Route::get('/get-leave-requests', 'LeaveRequestController@get_leave_requests');

  // LeaveTask routes
  Route::post('/leave-task', 'LeaveTaskController@store');
  //Subordinate response leave task
  Route::put('/response-leave-task', 'LeaveTaskController@response_leave_task');
  //Get all leave task by leave request id
  Route::get('/leave-tasks-by-leave-request', 'LeaveTaskController@get_leave_tasks_by_leave_request');

});
