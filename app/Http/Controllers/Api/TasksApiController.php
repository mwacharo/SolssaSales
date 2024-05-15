<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('user','deal')->get();

        return response()->json([
            'tasks' => $tasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'task_description',
            'due_date',
            'deal_id',
            'user_id',
            'completed'
        ]);



        $task = Task::create($data);

        return response()->json([
            'task' => $task,
            'message' => 'Task created successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->only([
            'task_description',
            'due_date',
            'deal_id',
            'user_id',
            'completed'
        ]);

        $task->update($data);

        return response()->json([
            'task' => $task,
            'message' => 'Task updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.'
        ]);
    }
}