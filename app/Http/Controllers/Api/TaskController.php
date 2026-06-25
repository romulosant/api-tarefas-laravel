<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;

class taskController extends Controller
{
    public function index(){
        $task = task::with('status')->get();

        return response()->json($task);
    }

    public function store(Request $request)
{
    $task = Task::create([
        'title' => $request->title,
        'description' => $request->description,
        'status_id' => $request->status_id ?? Status::PENDING 
    ]);

    return response()->json($task, 201);
}


}


