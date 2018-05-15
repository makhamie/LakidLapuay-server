<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;
use App\LeaveTask;
use App\Relation;

class LeaveRequestController extends Controller
{
    //
    public function store(Request $request) {
        $request_user = $request->user();
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
        $user = $request->user();
        if($user->role == 'supervisor') {
            $all_subordinate_id = Relation::select('subordinate_id')->where(['supervisor_id' => $user->id])->get();
            $subordinate_id_array = [];
            for ($i = 0; $i<count($all_subordinate_id); $i++) {
                $subordinate_id_array[$i] = $all_subordinate_id[$i]->subordinate_id;
            }
            $leave_requests = LeaveRequest::whereIn('subordinate_id', $subordinate_id_array)->whereNull('approved_at')->whereNull('rejected_at')->get();
            return [
                'message' => 'success',
                'results' => $leave_requests,
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
