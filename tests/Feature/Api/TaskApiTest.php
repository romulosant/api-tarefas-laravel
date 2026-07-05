<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Status;
class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private function criarStatusPadrao(): void
    {
        DB::table('statuses')->insert([
            [
                'id' => 1,
                'name' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'in_progress',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'done',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function test_lista_tarefas_com_sucesso(): void
    {
        $this->criarStatusPadrao();

        Task::factory()->count(3)->create([
            'status_id' => 1,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertOk();
    }

    public function test_cria_tarefa_com_sucesso(): void
    {
        $this->criarStatusPadrao();

        $response = $this->postJson('/api/tasks', [
            'title' => 'Estudar Laravel',
            'description' => 'Aprender testes de API',
            'status_id' => 1,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Estudar Laravel',
            'status_id' => 1,
        ]);
    }

    public function test_nao_cria_tarefa_sem_titulo(): void
    {
        $this->criarStatusPadrao();

        $response = $this->postJson('/api/tasks', [
            'description' => 'Tarefa sem título',
            'status_id' => 1,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_visualiza_uma_tarefa_com_sucesso(): void
    {
        $this->criarStatusPadrao();

        $task = Task::factory()->create([
            'status_id' => 1,
        ]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $task->id,
                'title' => $task->title,
            ]);
    }

    public function test_atualiza_uma_tarefa_com_sucesso(): void
    {
        $this->criarStatusPadrao();

        $task = Task::factory()->create([
            'title' => 'Título antigo',
            'status_id' => 1,
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}", [
            'title' => 'Título atualizado',
            'description' => 'Descrição atualizada',
            'status_id' => 2,
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Título atualizado',
            'status_id' => 2,
        ]);
    }

    public function test_conclui_uma_tarefa_com_sucesso(): void
    {
        $this->criarStatusPadrao();

        $task = Task::factory()->create([
            'status_id' => 1,
        ]);

        $response = $this->patchJson("/api/tasks/{$task->id}/complete");

        $response->assertOk();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status_id' => Status::DONE,
        ]);
    }

    public function test_deleta_uma_tarefa_com_sucesso(): void
    {
        $this->criarStatusPadrao();
    
        $task = Task::factory()->create([
            'status_id' => 1,
        ]);
    
        $response = $this->deleteJson("/api/tasks/{$task->id}");
    
        $response->assertNoContent();
    
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }


    public function test_filtra_tarefas_por_status(): void
    {
        $this->criarStatusPadrao();

        Task::factory()->create([
            'title' => 'Tarefa pendente',
            'status_id' => 1,
        ]);

        Task::factory()->create([
            'title' => 'Tarefa concluída',
            'status_id' => 3,
        ]);

        $response = $this->getJson('/api/tasks?status_id=3');

        $response->assertOk();
    }

    public function test_busca_tarefa_por_titulo(): void
    {
        $this->criarStatusPadrao();

        Task::factory()->create([
            'title' => 'Aprender Laravel API',
            'status_id' => 1,
        ]);

        Task::factory()->create([
            'title' => 'Estudar React',
            'status_id' => 1,
        ]);

        $response = $this->getJson('/api/tasks?search=Laravel');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Aprender Laravel API',
            ]);
    }

    public function test_lista_tarefas_com_paginacao(): void
    {
        $this->criarStatusPadrao();

        Task::factory()->count(15)->create([
            'status_id' => 1,
        ]);

        $response = $this->getJson('/api/tasks?page=1');

        $response->assertOk();
    }
}