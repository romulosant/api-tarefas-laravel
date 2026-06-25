<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;

class taskController extends Controller
{
    public function index()
    {
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

    public function show(int $id)
    {
        $task = Task::with('status')->findOrFail($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    public function update(Request $request, int $id)
    {
        $task = Task::with('status')->findOrFail($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'status_id' => $request->status_id ?? Status::PENDING
        ]);

        return response()->json($task->fresh()->load('status'));
    }


    public function destroy(int $id)
    {
        $task = Task::with('status')->findOrFail($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 204);
    }



    public function complete(int $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status_id' => Status::DONE
        ]);

        return response()->json(
            $task->fresh()->load('status')
        );
    }
}
