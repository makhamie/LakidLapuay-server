<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Task as Task;
use App\LeaveTask;

use Config;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $PER_PAGE = Config::get('constants.PER_PAGE');
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        $now = Carbon::now('Asia/Bangkok')->toDateString();
        // $now = Carbon::now('Asia/Bangkok');
        $subordinate = $request->user();
        if($request->has('action')) {
            $request_action = $request->input('action');
            if($request_action == 'pending'){
                $tasks = Task::where(['subordinate_id' => $subordinate->id])
                ->whereDate('started_at', '>', $now);
            }else if($request_action == 'doing'){
                $tasks = Task::where(['subordinate_id' => $subordinate->id])
                ->whereDate('finished_at', '>', $now);
            }else if($request_action == 'finished'){
                $tasks = Task::where(['subordinate_id' => $subordinate->id])
                ->whereDate('finished_at', '<', $now);
            }          
        }
        return [
            'results' => [
                'tasks' => $tasks->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                'count' => $tasks->count()
            ],
            'message' => 'Get history tasks',
            'success' => true
        ];
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
                $tasks_array = [];
    
                for ($i = 0; $i<count($tasks) ; $i++) {
                    if(LeaveTask::where('task_id', '=', $tasks[$i]->id)->doesntExist()){
                        $tasks_array[$i] = Task::find($tasks[$i]->id);
                    }
                    
                }
                return [
                    'message' => 'successful',
                    'results' => $tasks_array,
                    // 'array' => $leave_tasks_array,
                    // 'leave_tasks' => $leave_tasks,
                    // 'leave_tasks_array' => $leave_tasks_array,
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

    // public function get_history_tasks(Request $request) {
    //     return Task::all();
    // }
}