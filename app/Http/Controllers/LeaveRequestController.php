<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;
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

    public function substitute_approve(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $leave_request->update([
            'approved_at' => new Date()
        ]);
        return [
            'message' => 'Substituter approve',
            'results' => $leave_request,
            'success' => true
        ];
    }

    //Wait for test

    public function supervisor_approve(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $leave_request->update([
            'approved_at' => new Date()
        ]);
        return [
            'message' => 'Supervisor approve',
            'results' => $leave_request,
            'success' => true
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
            $leave_requests = LeaveRequest::whereIn('subordinate_id', $subordinate_id_array)->get();
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
