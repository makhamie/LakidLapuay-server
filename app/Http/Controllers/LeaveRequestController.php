<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;
use App\LeaveTask;
use App\Relation;
use Config;

class LeaveRequestController extends Controller
{
    //
    public function store(Request $request) {
        $request_user = $request->user();
        $startDate = $request->get('started_at');
        $finishDate = $request->get('finished_at');
        //เช็คว่าช่วงเวลาดังกล่าว User ไม่ได้มี leave request อยู่แล้ว
        // $id_request = LeaveRequest::select('id')->whereBetween('started_at', array($startDate,$finishDate))
        //             ->orWhereBetween('finished_at', array($startDate,$finishDate))->get();
        $user_request = LeaveRequest::whereBetween('started_at', array($startDate,$finishDate))
                    ->orWhereBetween('finished_at', array($startDate,$finishDate))->count();
        //และ User ไม่ได้เป็น substitute ของ Leave Task อันไหน 
        // $substitute_request = LeaveTask::with('leave_request')
        //                         ->where('substitute_id',$request_user->id)
        //                         ->get();
        if($user_request == 0){
            $created = LeaveRequest::create([
                'subordinate_id' => $request_user->id,
                'reason' => $request->get('reason'),
                // 'approved_at' => $request->get('approved_at'),
                'started_at' => $request->get('started_at'),
                'finished_at' => $request->get('finished_at'),
                // 'rejected_at' => $request->get('rejected_at')
            ]);
            return [
                'message' => 'Create leave request successful',
                // 'subsutitute' => $substitute_request,
                // 'id_request' => $id_request,
                'results' => $created,
                'success' => true
            ];
        }
        return [
            // 'user request' => $user_request,
            // 'count' => $substitute_request,
            // 'id_request' => $id_request,
            'message' => 'You has leave request in this range already or you are',
            'success' => false
        ];
    }

    //Wait for test

    public function response_leave_request(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $request_action = $request->get('action');
        if($leave_request->rejected_at == null && $leave_request->approved_at == null){
            if($request_action == 'approved'){
                $leave_request->update([
                    'approved_at' => now()
                ]);
                // Check for update task of leave request
                // update subordinate if of these task
                $leave_tasks = LeaveTask::where(['leave_request_id' => $leave_request_id]);
                for ($i = 0; $i<count($leave_tasks); $i++) {
                    $task = Task::find($leave_tasks[$i]->task_id);
                    $task->update([
                        'subordinate_id' => $leave_tasks->substitute_id
                    ]);
                }
                return [
                    'message' => 'Supervisor approved',
                    'results' => $leave_request,
                    'success' => true
                ];
            }else if($request_action == 'rejected'){
                $leave_request->update([
                    'rejected_at' => now()
                ]);
                return [
                    'message' => 'Supervisor rejected',
                    'results' => $leave_request,
                    'success' => true
                ];
            }
        }
        
        return [
            'message' => 'Action error',
           'success' => false
        ];
    }

    public function get_leave_requests(Request $request) {
        $PER_PAGE = Config::get('constants.PER_PAGE');
        $page = 1;
        $count = 0;
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        $user = $request->user();
        if($user->role == 'supervisor') {
            $all_subordinate_id = Relation::select('subordinate_id')->where(['supervisor_id' => $user->id])->get();
            $subordinate_id_array = [];
            for ($i = 0; $i<count($all_subordinate_id); $i++) {
                $subordinate_id_array[$i] = $all_subordinate_id[$i]->subordinate_id;
            }
            $leave_requests =  LeaveRequest::whereIn('subordinate_id', $subordinate_id_array);
            if ($request->has('request')) {
                if($request->input('request') == 'pending'){
                    $leave_requests =  LeaveRequest::whereIn('subordinate_id', $subordinate_id_array)->whereNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'approved'){
                    $leave_requests =  LeaveRequest::whereIn('subordinate_id', $subordinate_id_array)->whereNotNull('approved_at')->whereNull('rejected_at');
                }else if($request->input('request') == 'rejected'){
                    $leave_requests =  LeaveRequest::whereIn('subordinate_id', $subordinate_id_array)->whereNull('approved_at')->whereNotNull('rejected_at');
                }
            }
            
            $count = $leave_requests->count();
            return [
                'message' => 'success',
                'results' => [
                    'leave_requests' => $leave_requests->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                    'count' => $count
                ],
                'success' => true
            ];
        }
        return [
            'message' => 'Need supervisor privilege',
            'success' => false
        ];
    }

    // public function get_substitute_request(Request $request) {
        
    // }
}
