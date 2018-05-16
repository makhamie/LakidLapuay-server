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
        $user_request = LeaveRequest::whereBetween('started_at', array($startDate,$finishDate))
                    ->orWhereBetween('finished_at', array($startDate,$finishDate))->count();
        //และ User ไม่ได้เป็น substitute ของ Leave Task อันไหน 
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
                'results' => $created,
                'success' => true
            ];
        }
        return [
            'message' => 'You has leave request in this range already',
            'success' => false
        ];
    }

    //Wait for test

    public function response_leave_request(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $request_action = $request->get('action');
        if($request_action == 'approved'){
            $leave_request->update([
                'approved_at' => now()
            ]);
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
                    'leave-requests' => $leave_requests->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
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
