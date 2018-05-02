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
        Task::create($request->all());
        return [
            'message' => 'Create Task successful'
        ];
    }
    //
}
