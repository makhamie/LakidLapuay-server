<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveTask;

class LeaveTaskController extends Controller
{
    //
    public function store(Request $request) {
        $request_user = $request->user();
        $created = LeaveTask::create([
            'leave_request_id' => $request->get('leave_request_id'),
            'task_id' => $request->get('task_id'),
            'substitute_id' => $request->get('substitute_id')
        ]);
        return [
            'message' => 'Create leave request successful',
            'results' => $created,
            'success' => true
        ];
    }

   
    public function get_leave_tasks_by_leave_request(Request $request) {
        $user = $request->user();
        if($user->role == 'subordinate' || $user->role == 'supervisor') {
            $leave_request_id = $request->input('id');
            $leave_tasks_of_leave_request = LeaveTask::where(['leave_request_id' => $leave_request_id])->get();
            return [
                'message' => 'success',
                'results' => $leave_tasks_of_leave_request,
                'success' => true
            ];
        }
        return [
            'message' => 'Need user privilege',
            'success' => false
        ];
    }

    // public function get_substitute_request(Request $request) {
        
    // }
}
