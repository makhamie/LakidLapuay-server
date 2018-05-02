<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relation;
use App\User;

class RelationController extends Controller
{
    public function store(Request $request) {
        if ($request->user()->role == 'admin') {
            Relation::create($request->all());
            return [
                "message" => "Subordinator ".$request->get('subordinate_id')." has been assign to Supervisor ".$request->get('supervisor_id').".",
                "success" => true
            ];
        }
        return [
            "message" => "Need admin privilege",
            "success" => false
        ];   
    }

    public function get_subordinates(Request $request) {
        $supervisor = $request->user();
        if($supervisor->role == 'supervisor') {
            $all_subordinate_id = Relation::select('subordinate_id')->where(['supervisor_id' => $supervisor->id])->get();
            $all_subordinate = User::whereIn(['id', $all_subordinate_id])->get();
            return [
                "message" => "successful",
                "result" => $all_subordinate,
                "success" => true
            ];
        }
        return [
            "message" => "Need supervisor privilege",
            "success" => false
        ];
    }

    public function get_friends(Request $request) {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            $supervisor_id = Relation::select('subordinate_id')->where(['supervisor_id' => $subordinate->id])->get();
            $friends = User::where(['id' => $supervisor_id]);
            return [
                "message" => "successful",
                "result" => $friends,
                "success" => true
            ];
        }
        return [
            "message" => "Need subordinate privilege",
            "success" => false
        ];
    }

    public function index(Request $request) {
        if($request->user()->role == 'admin') {
            $relations = Relation::all();
            return [
                "message" => "sucessful",
                "result" => $relations,
                "success" => true
            ];
        }
        return [
            "message" => "Need admin privilege",
            "success" => false
        ];
    }
}
