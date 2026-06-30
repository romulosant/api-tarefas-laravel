<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;

class taskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('status');
    
        // Filtro por status
        if($request->filled('status_id')){
            $query->where('status_id', $request->status_id);
        }
    
        // Filtro por título 
        if($request->filled('title')){
            $query->where('title', 'like', '%' . $request->title . '%');
        }
    
        // Filtro por data
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    
        $query->orderBy('created_at', 'desc');
    
        $tasks = $query->paginate(15);
    
        // estruturando as mensagens
        return response()->json([
            'message' => 'Tarefas listadas com sucesso',
            'data' => TaskResource::collection($tasks),
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
                'last_page' => $tasks->lastPage(),
            ]
        ]);
    }

    public function store(StoreTaskRequest $request)
{
    $data = $request->validated();
    
    // Se não informar status, usa PENDING
    $data['status_id'] = $data['status_id'] ?? Status::PENDING;
    
    // Cria tarefa
    $task = Task::create($data);
    
    // Carrega relacionamento status
    $task = $task->load('status');
    
    // Retorna a TAREFA CRIADA (não coleção)
    return response()->json([
        'message' => 'Tarefa criada com sucesso',
        'data' => new TaskResource($task)  // ✅ new, não collection
    ], 201);  // ✅ Status 201 (Created)
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
