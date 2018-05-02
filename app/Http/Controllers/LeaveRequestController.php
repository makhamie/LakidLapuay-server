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
            'requester_id' => $request_user->id,
            'substitute_id' => $request->get('substitute_id'),
            'start' => $request->get('start'),
            'end' => $request->get('end'),
            'task_id' => $request->get('task_id'),
            'reason' => $request->get('reason'),
        ]);
        return [
            'message' => 'Create leave request successful',
            'result' => $created,
            'success' => true
        ];
    }
}
