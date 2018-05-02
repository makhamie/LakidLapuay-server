<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task as Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::all();
    }

    public function show($id)
    {
        return Task::find($id);
    }

    public function store(Request $request)
    {
        $created =Task::create($request->all());
        return [
            'message' => 'Create Task successful',
            'result' => $created,
            'success' => true
        ];
    }

    public function get_supervisor_task(Request $request) 
    {
        $supervisor = $request->user();
        if($supervisor->role == 'supervisor') {
            $all_task = Task::where(['owner_id' => $supervisor->id])->get();
            return [
                'message' => 'successful',
                'result' => $all_task,
                'success' => true
            ];
        }
    }
    //
}
