<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::with('status');

        // Filtro por status
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // Filtro por título
        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }

        // Filtro por data
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'message' => 'Tarefas listadas com sucesso',
            'data' => TaskResource::collection($tasks),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ],
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Se não informar status, usa PENDING
        $data['status_id'] = $data['status_id'] ?? Status::PENDING;

        $task = Task::create($data)->load('status');

        return response()->json([
            'message' => 'Tarefa criada com sucesso',
            'data' => new TaskResource($task),
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $task->load('status');

        return response()->json([
            'message' => 'Tarefa encontrada com sucesso',
            'data' => new TaskResource($task),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso',
            'data' => new TaskResource($task->fresh()->load('status')),
        ]);
    }

    public function destroy(Task $task): Response
    {
        $task->delete();

        return response()->noContent();
    }

    public function complete(Task $task): JsonResponse
    {
        $task->status_id = Status::DONE;
        $task->save();

        $task->refresh();
        $task->load('status');

        return response()->json([
            'message' => 'Tarefa concluída com sucesso',
            'data' => new TaskResource($task),
        ]);
    }
}
