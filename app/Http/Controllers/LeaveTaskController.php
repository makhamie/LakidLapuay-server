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

    //Wait for test

    public function response_leave_task(Request $request) {
        $leave_task_id = $request->input('leave_task_id');
        $leave_request = LeaveTask::find($leave_task_id);
        $request_action = $request->get('action');
        if($request_action == 'approved'){
            $leave_request->update([
                'approved_at' => now()
            ]);
            return [
                'message' => 'Substituter accepted this request',
                'results' => $leave_request,
                'success' => true
            ];
        }else if($request_action == 'rejected'){
            $leave_request->update([
                'rejected_at' => now()
            ]);
            return [
                'message' => 'Substituter rejected this request',
                'results' => $leave_request,
                'success' => true
            ];
        }
        return [
            'message' => 'Action error',
           'success' => false
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
