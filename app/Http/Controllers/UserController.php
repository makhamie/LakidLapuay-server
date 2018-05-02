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
        
        if($request->user()->role == 'admin') {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'address' => $request->get('address'),
                'facebook' => $request->get('facebook'),
                'instagram' => $request->get('instagram'),
                'line' => $request->get('line'),
                'department_id' => $request->get('department_id'),
                'role' => $request->get('role'),
                'password' => Hash::make($request->get('password')),
            ]);
            return [
                'message' => 'Create user successful',
                'result' => $user,
                'success' => true
            ];
        }
        return [
            'message' => 'Need admin privilege',
            'success' => false
        ];
       

    }

    public function index() {
        return User::all();
    }

    public function get_user_by_role(Request $request) {
        if($request->user()->role == 'admin') {
            if($request->has('role')) {
                $role = $request->input('role');
                $users = User::where(['role' => $role])->get();
                return [
                    "message" => "success",
                    "result" => $users,
                    "success" => true
                ];
            }
            return [
                "message" => "Please Specify role",
                "success" => false
            ];
        }
            
       return [
           "message" => 'Need Admin Privilege',
           "success" => false
       ];
    }

    public function get_user(Request $request) {
        return [
            'message' => 'successful',
            'result' => $request->user(),
            'success' => true
        ];
    }

    public function change_detail(Request $request, $id) {
        $user = User::where(['id' => $id])->first();
            
        if($user) {
            $user->update([
                'address' => $request->get('address'),
                'facebook' => $request->get('facebook'),
                'instagram' => $request->get('instagram'),
                'line' => $request->get('line'),
                'profile_picture' => $request->get('profile_picture'),
            ]);
            return [
                'message' => 'Sucessfully Change user '.$user->name.' detail'
            ];
            
        }else {
            return [
                'message' => 'Cannot find user'
            ];
        }
    }

    public function change_department(Request $request, $id) {
        if($request->user()->role == 'admin') {
            $user = User::where(['id' => $id])->first();
            
            if($user) {
                $user->update([
                    'department_id' => $request->get('department_id')
                ]);
                return [
                    'message' => 'Sucessfully Change user '.$user->name.' to Department_id '.$request->get('department_id')
                ];
                
            }else {
                return [
                    'message' => 'Cannot find user'
                ];
            }
        }
        return [
            'message' => 'Need admin privilege'
        ];
    }
}