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
            $subordinate_id_array = [];
            for ($i = 0; $i<count($all_subordinate_id); $i++) {
                $subordinate_id_array[$i] = $all_subordinate_id[$i]->subordinate_id;
            }
            // return [
            //     "resule" => $array
            // ];
            $all_subordinate = User::whereIn('id', $subordinate_id_array)->get();
            return [
                "message" => "successful",
                "results" => $all_subordinate,
                "success" => true
            ];
        }
        return [
            "message" => "Need supervisor privilege",
            "success" => false
        ];
    }

    public function get_collations(Request $request) {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            $supervisor_id = Relation::select('supervisor_id')->where(['subordinate_id' => $subordinate->id])->get()->first();
            $id_subordinates = Relation::select('subordinate_id')->where(['supervisor_id' => $supervisor_id->supervisor_id])->get();
            $subordinate_id_array = [];
            for ($i = 0; $i<count($id_subordinates) ; $i++) {
                if($id_subordinates[$i]->subordinate_id != $subordinate->id){
                    $subordinate_id_array[$i] = $id_subordinates[$i]->subordinate_id;    
                }
                
            }
            $all_subordinate = User::select('id','name')->whereIn('id', $subordinate_id_array)->get();
            return [
                "message" => "successful",
                "results" => $all_subordinate,
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
                "results" => $relations,
                "success" => true
            ];
        }
        return [
            "message" => "Need admin privilege",
            "success" => false
        ];
    }
}
