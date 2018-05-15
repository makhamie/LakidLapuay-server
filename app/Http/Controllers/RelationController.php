<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relation;
use App\User;
use App\Task;
use App\LeaveRequest;

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

    public function update_user_relation(Request $request) {
        if ($request->user()->role == 'admin') {
            $subordinate = $request->get('subordinate_id');
            $supervisor = $request->get('supervisor_id');
            $request_relation = Relation::where(['subordinate_id'=> $subordinate])->first();
            if ($request_relation == null) {
                $request_relation = Relation::create($request->all());
                return [
                    "message" => 'Successfully create relation',
                    "success" => true,
                    "results" => $request_relation
                ];
            } else {
                $request_relation->update([
                    'supervisor_id' => $supervisor,
                ]);
                return [
                    "message" => 'Update supervisor of this user',
                    "success" => true,
                    "results" => $request_relation
                ];
            }
           
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

    public function get_available_collation_by_range(Request $request) {
        $subordinate = $request->user();
        if($subordinate->role == 'subordinate') {
            if($request->has('start')){
                $startDate = $request->input('start');
            }
            if($request->has('finish')){
                $finishDate = $request->input('finish');
            }
            $supervisor_id = Relation::select('supervisor_id')->where(['subordinate_id' => $subordinate->id])->get()->first();
            $id_subordinates = Relation::select('subordinate_id')->where(['supervisor_id' => $supervisor_id->supervisor_id])->get();
            $subordinate_id_array = [];
            for ($i = 0; $i<count($id_subordinates) ; $i++) {
                if($id_subordinates[$i]->subordinate_id != $subordinate->id){
                    $subordinate_task = Task::where(['subordinate_id' => $id_subordinates[$i]->subordinate_id])
                                        ->where(function ($query) use ($startDate, $finishDate) {
                                            $query->whereBetween('started_at',array($startDate,$finishDate))
                                                    ->orWhereBetween('finished_at',array($startDate,$finishDate));
                                        })
                                        ->first();
                    $subordinate_leave = LeaveRequest::where(['subordinate_id' => $id_subordinates[$i]->subordinate_id])
                                        ->where(function ($query) use ($startDate, $finishDate) {
                                            $query->whereBetween('started_at',array($startDate,$finishDate))
                                                    ->orWhereBetween('finished_at',array($startDate,$finishDate));
                                        })
                                        ->first();
                    if($subordinate_task == null && $subordinate_leave == null) {
                        $subordinate_id_array[$i] = $id_subordinates[$i]->subordinate_id;    
                    }
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


    public function get_supervisor_by_id(Request $request) {
        if($request->has('id')) {
            $subordinate_id = $request->input('id');
            // $relation = Relation::with('user')->where(['subordinate_id' => $subordinate_id])->first();
            $relation = Relation::where(['subordinate_id' => $subordinate_id])->first();
            if ($relation != null) {
                $supervisor = $relation->supervisor;
                return [
                    "message" => "Get supervisor successful",
                    "results" => $supervisor,
                    "success" => true
                ];
            }
            return [
                "message" => 'No relation could be found',
                "results" => [
                    "id" => -1,
                    "name" => "No Supervisor"
                ],
                "success" => true
            ];
            
        }
        return [
            "message" => "Request don't have id in query string",
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
