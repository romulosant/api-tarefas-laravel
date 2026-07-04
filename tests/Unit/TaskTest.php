<?php

namespace Tests\Unit;

use App\Models\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function test_task_tem_campos_preenchiveis(): void
    {
        $task = new Task();

        $this->assertEquals([
            'title',
            'description',
            'status',
        ], $task->getFillable());
    }
}