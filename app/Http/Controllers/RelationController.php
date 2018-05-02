<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Relation;

class RelationController extends Controller
{
    public function store(Request $request) {
        if ($request->user()->role == 'admin') {
            Relation::create($request->all());
            return [
                "message" => "Subordinator ".$request->get('subordinate_id')." has been assign to Supervisor ".$request->get('supervisor_id')."."
            ];
        }
        return [
            "message" => "Need admin privilege"
        ];   
    }

    public function index(Request $request) {
        if($request->user()->role == 'admin') {
            $relations = Relation::all();
            return [
                "message" => "sucessful",
                "data" => $relations
            ];
        }
        return [
            "message" => "Need admin privilege"
        ];
    }
}
