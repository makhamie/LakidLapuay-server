<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Department as Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::all();
    }

    public function show($id)
    {
        return Department::find($id);
    }

    public function store(Request $request)
    {
        Department::create($request->all());
        $response = true;
        return response()->json($response);
    }
    //
}
