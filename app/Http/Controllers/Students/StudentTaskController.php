<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Models\Student;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class StudentTaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Student $student, Mosque $mosque)
    {
        return $this->taskService->index($mosque);
    }

    public function show(Student $student, Mosque $mosque, Task $task)
    {
        return $this->taskService->show($task);
    }
}
