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
        
        // if($request->user()->role == 'admin') {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'department_id' => $request->get('department_id'),
                'role' => $request->get('role'),
                'password' => Hash::make($request->get('password')),
            ]);
            return [
                'message' => 'Create user successful',
                'results' => $user,
                'success' => true
            ];
        // }
        // return [
        //     'message' => 'Need admin privilege',
        //     'success' => false
        // ];
       

    }

    public function user_update_profile(Request $request) {
        if($request->user()) {
            $user = $request->user();
            
            $user->update([
                'address' => $request->get('address'),
                'facebook' => $request->get('facebook'),
                'instagram' => $request->get('instagram'),
                'line' => $request->get('line')
                // 'profile_picture' => $request->get('profile_picture'),
            ]);
            return [
                'message' => 'Sucessfully Change user '.$user->name.' to Department_id '.$request->get('department_id')
            ];
        }
        return [
            'message' => 'Need authorization'
        ];
    }

    public function get_available_supervisor(Request $request) {
        if ($request->user()->role == 'admin') {
            if($request->has('department_id')) {
                $department_id = $request->input('department_id');
                return [
                    'message' => 'successful',
                    'results' => User::where(['role' => 'supervisor'])->where(['department_id' => $department_id])->get(),
                    'success' => true    
                ];
            }
            return [
                'message' => 'department_id not found in query string',
                'success' => false
            ];
        }
        return [
            'message' => 'need admin authorization',
            'success' => false
        ];
    }

    public function index(Request $request) {
        $PER_PAGE = 10;
        $page = 1;
        if ($request->has('page')) {
            $page = $request->input('page');
        }
        return [
                'messages' => 'Get user successful',
                'results' => [
                    'users' => User::with('department')->skip(($page-1)*$PER_PAGE)->take($PER_PAGE)->get(),
                    'count' => User::all()->count()
                ],
                'success' => true
            ];
        // return User::all()->department();
    }

    public function admin_get_user_by_role(Request $request) {
        if($request->user()->role == 'admin') {
            if($request->has('role')) {
                $role = $request->input('role');
                $users = User::where(['role' => $role])->get();
                return [
                    "message" => "success",
                    "results" => $users,
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

    public function admin_get_user(Request $request) {
        return [
            'message' => 'successful',
            'results' => $request->user(),
            'success' => true
        ];
    }

    public function get_user_role(Request $request) {
        return [
            'message' => 'successful',
            'results' => $request->user()->role,
            'success' => true
        ];
    }

    public function admin_manage_user(Request $request, $id) {
        if($request->user()->role == 'admin') {
            $user = User::where(['id' => $id])->first();
            if($user) {
                $new_department = $request->get('department_id');
                $new_role = $request->get('role');
                if($new_department){
                    $user->update([
                        'department_id' => $new_department
                    ]);
                }
                if($new_role){
                    $user->update([
                        'role' => $new_role
                    ]);
                }
                return [
                    'message' => 'Sucessfully Change user '.$user->name.' detail',
                    'results' => $user,
                    'success' => true
                ];
                
            }else {
                return [
                    'message' => 'Cannot find user',
                    'success' => false
                ];
            }
        }
        return [
            'message' => 'Permission denied',
            'success' => false
        ]; 
    }

//     public function change_department(Request $request, $id) {
//         if($request->user()->role == 'admin') {
//             $user = User::where(['id' => $id])->first();
            
//             if($user) {
//                 $user->update([
//                     'department_id' => $request->get('department_id')
//                 ]);
//                 return [
//                     'message' => 'Sucessfully Change user '.$user->name.' to Department_id '.$request->get('department_id')
//                 ];
                
//             }else {
//                 return [
//                     'message' => 'Cannot find user'
//                 ];
//             }
//         }
//         return [
//             'message' => 'Need admin privilege'
//         ];
//     }
}