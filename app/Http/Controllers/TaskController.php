<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProjectId = $request->get('project_id');

        $tasksQuery = Task::with('project')->orderBy('priority');

        if ($selectedProjectId) {
            $tasksQuery->where('project_id', $selectedProjectId);
        }

        $tasks = $tasksQuery->get();

        return view('tasks.index', compact('tasks', 'projects', 'selectedProjectId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $priority = Task::getNextPriority($request->project_id);

        Task::create([
            'name' => $request->name,
            'priority' => $priority,
            'project_id' => $request->project_id
        ]);

        return redirect()->route('tasks.index', ['project_id' => $request->project_id])
                        ->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task->update([
            'name' => $request->name,
            'project_id' => $request->project_id
        ]);

        return redirect()->route('tasks.index', ['project_id' => $request->project_id])
                        ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('tasks.index', ['project_id' => $projectId])
                        ->with('success', 'Task deleted successfully!');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        Task::reorderTasks($request->task_ids, $request->project_id);

        return response()->json(['success' => true]);
    }
}
