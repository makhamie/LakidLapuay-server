<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request) {
        if(User::where(['email' => $request->get('email')])->first()) {
            return ['message' => 'email is already used'];
        }

        // if($request->)
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'address' => $request->get('address'),
            'facebook' => $request->get('facebook'),
            'instagram' => $request->get('instagram'),
            'line' => $request->get('line'),
            'department_id' => $request->get('department_id'),
            'role' => $request->get('role'),
            'password' => Hash::make($request->get('name')),
        ]);
        // return User::create($request->all());
    }

    public function index() {
        return User::all();
    }
}
