<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Task as Task;
use Config;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $PER_PAGE = Config::get('constants.PER_PAGE');
        if ($request->has('page')) {
            $page = $request->input('page');
            return [
                'messages' => 'Get task successful',
                'results' => [
                    'tasks' => Task::skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                    'count' => Task::all()->count()
                ],
                'success' => true
            ];
        }
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
        $PER_PAGE = Config::get('constants.PER_PAGE');
        $supervisor = $request->user();
        if($supervisor->role == 'supervisor' && $request->has('page')) {
            $page = $request->input('page');
            return [
                'message' => 'successful',
                'results' => [
                    'tasks' => Task::where(['supervisor_id' => $supervisor->id])->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                    'count' => Task::where(['supervisor_id' => $supervisor->id])->count()
                ],
                'success' => true
            ];
        }
        return [
            'message' => 'Need supervisor privilege or page',
            'success' => false
        ];
    }

    public function get_subordinate_tasks(Request $request) 
    {
        $PER_PAGE = Config::get('constants.PER_PAGE');
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate' && $request->has('page')) {
            $page = $request->input('page');
            return [
                'message' => 'successful',
                'results' => [
                    'tasks' => Task::where(['subordinate_id' => $subordinate->id])->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                    'count' => Task::where(['subordinate_id' => $subordinate->id])->count()
                ],
                'success' => true
            ];
        }
        return [
            'message' => 'Need subordinate privilege or page',
            'success' => false
        ];
    }

    // create task
    public function get_tasks_in_range(Request $request) 
    {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            if($request->has('start') && $request->has('finish')) {
                $startDate = $request->input('start');
                $finishDate = $request->input('finish');
                $tasks = Task::where(['subordinate_id' => $subordinate->id])
                ->where(function ($query) use ($startDate,$finishDate){
                    $query->whereBetween('started_at',array($startDate,$finishDate))
                    ->orWhereBetween('finished_at',array($startDate,$finishDate));
                })
                ->get();
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