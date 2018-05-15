<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveTask;
use Config;

class LeaveTaskController extends Controller
{
    //
    public function store(Request $request) {
        $request_user = $request->user();
        $array = $request->get('leave_task_array');
        $created = LeaveTask::insert($array);
        // $created = LeaveTask::create([
        //     'leave_request_id' => $request->get('leave_request_id'),
        //     'task_id' => $request->get('task_id'),
        //     'substitute_id' => $request->get('substitute_id')
        // ]);
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
            $all_leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request->id]);
            $leave_tasks_of_leave_request = $all_leave_tasks->get();
            $count = $all_leave_tasks->count();
            $count_unreponsed_task = $all_leave_tasks->whereNull('approved_at')->whereNull('rejected_at')->count();
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
   
    public function get_leave_tasks_of_substitute (Request $request) {
        $PER_PAGE = Config::get('constants.PER_PAGE');
        $page = 1;
        $count = 0;
        $user = $request->user();
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        if($user->role == 'subordinate') {
            
            if($request->has('request')){
                if($request->input('request') == 'pending'){
                    $leave_tasks = LeaveTask::with('leave_request', 'task', 'requester')->where(['substitute_id' => $user->id])->whereNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'approved'){
                    $leave_tasks = LeaveTask::with('leave_request', 'task', 'requester')->where(['substitute_id' => $user->id])->whereNotNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'rejected'){
                    $leave_tasks = LeaveTask::with('leave_request', 'task', 'requester')->whereNull('approved_at')->whereNotNull('rejected_at');
                }
            }else{
                $leave_tasks = LeaveTask::with('leave_request', 'task')->where(['substitute_id' => $user->id]);
            }
            $count = $leave_tasks->count();
            // return [
            //     'message' => $leave_tasks->get()
            // ];
            return [
                'message' => 'success',
                'results' => [
                    'count' => $count,
                    'leave_tasks' => $leave_tasks->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get()
                ],
                // 'count' => $count,
                'success' => true
            ];
        }
        return [
            'message' => 'Need user privilege',
            'success' => false
        ];
    }

    // public function get_leave_tasks_by_owner (Request $request) {
    //     $user = $request->user();
    //     if($user->role == 'subordinate') {
    //         $leave_tasks = LeaveTask::where(['substitute_id' => $user->id]);
            
    //         return [
    //             'message' => 'success',
    //             'results' => [
    //                 'count' => $leave_tasks->count(),
    //                 'list' => $leave_tasks->get()
    //             ],
    //             // 'count' => $count,
    //             'success' => true
    //         ];
    //     }
    //     return [
    //         'message' => 'Need user privilege',
    //         'success' => false
    //     ];
    // }

    public function get_leave_tasks_by_leave_request(Request $request) {
        $user = $request->user();
        if($user->role == 'subordinate' || $user->role == 'supervisor') {
            $leave_request_id = $request->input('id');
            if($request->has('request')){
                if($request->input('request') == 'pending'){
                    $leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request_id])->whereNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'approved'){
                    $leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request_id])->whereNotNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'rejected'){
                    $leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request_id])->whereNull('approved_at')->whereNotNull('rejected_at');
                }
            }else{
                $leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request_id]);
            }
            
            return [
                'message' => 'success',
                'results' => [
                    'count' => $leave_tasks->count(),
                    'list' => $leave_tasks->get()
                ],
                // 'count' => $count,
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
