<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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

    // create task
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

    public function get_subordinate_tasks(Request $request) 
    {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            $all_task = Task::where(['subordinate_id' => $subordinate->id])->get();
            return [
                'message' => 'successful',
                'results' => $all_task,
                'success' => true
            ];
        }
        return [
            'message' => 'Need subordinate privilege',
            'success' => false
        ];
    }

    // create task
    public function get_tasks_in_range(Request $request) 
    {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            if($request->has('start') && $request->has('finish')) {
                $startDate = new Carbon($request->input('start'));
                $finishDate = new Carbon($request->input('finish'));
                $tasks = Task::where(['subordinate_id' => $subordinate->id])->whereBetween('started_at',array($request->input('start'),$request->input('finish')))->orWhereBetween('finished_at',array($request->input('start'),$request->input('finish')))->where(['subordinate_id' => $subordinate->id])->get();
                return [
                    'message' => 'successful',
                    'results' => $tasks,
                    'success' => true
                ];
            }
            return [
                'message' => 'Need range',
            ];

        }
        return [
            'message' => 'Need subordinate privilege',
            'success' => false
        ];
    }
    
    public function update_task(Request $request)
    {
        $user = $request->user();
        if($user->role == 'supervisor') {
            $task_id = $request->get('task_id');
            $task = Task::find($task_id);

            $task->update([
                'subordinate_id' => $request->get('subordinate_id')
            ]);
            return [
                'task' => $task,
                'message' => 'This task has already updated',
                'success' => true
            ];
        }
        return [
            'message' => 'Need supervisor privilege',
            'success' => false
        ];
    }
}