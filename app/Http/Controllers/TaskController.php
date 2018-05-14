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
        $user = $request->user();
        $user_id = $user->id;
        if($user->role == 'supervisor') {
            $created =Task::create([
                'subordinate_id' => $request->get('subordinate_id'),
                'name' => $request->get('name'),
                'started_at' => $request->get('started_at'),
                'finished_at' => $request->get('finished_at'),
                'description' => $request->get('description'),
                'supervisor_id' => $user_id,
            ]);
            // $created =Task::create($request->all());
            return [
                'message' => 'Create Task successful',
                'results' => $created,
                'success' => true
            ];
        }
        return [
            'message' => 'Need supervisor privilege',
            'success' => false
        ];
    }

    public function get_supervisor_tasks(Request $request) 
    {
        $supervisor = $request->user();
        if($supervisor->role == 'supervisor') {
            $all_task = Task::where(['supervisor_id' => $supervisor->id])->get();
            return [
                'message' => 'successful',
                'results' => $all_task,
                'success' => true
            ];
        }
        return [
            'message' => 'Need supervisor privilege',
            'success' => false
        ];
    }
    
    public function update_subordinate(Request $request) {
        $user = $request->user();

        return [
            "user" => $user,
            "task" => $task
        ];
    }
    // public function update_subordinate(Request $request) {
    //     $user = $request->user();

    //     return [
    //         "user" => $user,
    //         "task" => $task
    //     ];
    // }

}
