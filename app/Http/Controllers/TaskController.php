<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $request->user()
                        ->tasks()
                        ->paginate(10);

        if($tasks->isEmpty()){
            return response()->json([
                'success' =>  false,
                'message' => 'No data, please create task.'
            ],404);
        }
        
        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium, high'],
            'due_date' => ['required', 'date']
        ]);



        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task created.',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        /*
            SELECT *
            FROM tasks
            WHERE user_id = 5
            AND id = 10
            LIMIT 1;
        */
        $task = $request
            ->user()
            ->tasks()
            ->find($id);

        if (!$task) {
            return response()->json(['message' => "Only owner can update this task."], 403);
        }

        $validated = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'priority' => ['sometimes', 'required', 'in:low,medium, high'],
            'due_date' => ['sometimes', 'date']
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated.',
            'data' => $task
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request ,string $id)
    {
        $task = $request->user()
                        ->tasks()
                        ->find($id);
        
        if(!$task) {
            return response()->json(['message' => "Only owner can delete this task."], 403);
        }  
        
        
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted.',
            'id' => $task->id
        ],200);
    }
}
