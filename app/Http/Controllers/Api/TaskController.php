<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Status;
use App\Models\Task;

class taskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('status')->get();

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();

        $data['status_id'] = $data['status_id'] ?? Status::PENDING;

        $task = Task::create($data);

        $task = $task->load('status');

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        $task = Task::with('status')->findOrFail($id);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = Task::findOrFail($id);

        $data = $request->validated();

        $task->update($data);

        return new TaskResource(
            $task->fresh()->load('status')
        );
    }

    public function destroy(int $id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->noContent();
    }

    public function complete(int $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'status_id' => Status::DONE,
        ]);

        return new TaskResource(
            $task->fresh()->load('status')
        );
    }
}
