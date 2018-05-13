<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LeaveRequest;

class LeaveRequestController extends Controller
{
    //
    public function store(Request $request) {
        $request_user = $request->user();
        $created = LeaveRequest::create([
            'subordinate_id' => $request_user->id,
            'reason' => $request->get('reason'),
            'approved_at' => $request->get('approved_at'),
            'started_at' => $request->get('started_at'),
            'finished_at' => $request->get('finished_at'),
            'rejected_at' => $request->get('rejected_at')
        ]);
        return [
            'message' => 'Create leave request successful',
            'result' => $created,
            'success' => true
        ];
    }

    //Wait for test

    public function substitute_approve(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $leave_request->update([
            'approved_by_substitute_at' => new Date()
        ]);
        return [
            'message' => 'Substituter approve',
            'result' => $leave_request,
            'success' => true
        ];
    }

    //Wait for test

    public function supervisor_approve(Request $request) {
        $leave_request_id = $request->get('leave_request_id');
        $leave_request = LeaveRequest::find($leave_request_id);
        $leave_request->update([
            'approved_by_supervisor_at' => new Date()
        ]);
        return [
            'message' => 'Supervisor approve',
            'result' => $leave_request,
            'success' => true
        ];
    }

    public function get_leave_requests(Request $request) {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            $subordinate_leave_requests = LeaveRequest::where(['requester_id', $subordinate->id])->get();
            return [
                'message' => 'success',
                'result' => $subordinate_leave_requests,
                'success' => true
            ];
        }
        return [
            'message' => 'Need subordinate privilege',
            'success' => false
        ];
    }

    // public function get_substitute_request(Request $request) {
        
    // }
}
