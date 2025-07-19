@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Tasks</h1>
    </div>

    <!-- Project Filter -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center space-x-4">
            <label for="project_id" class="text-sm font-medium text-gray-700">Filter by Project:</label>
            <select name="project_id" id="project_id" class="border border-gray-300 rounded-md px-3 py-2" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Add Task Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Add New Task</h2>
        <form method="POST" action="{{ route('tasks.store') }}" class="flex items-center space-x-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $selectedProjectId }}">
            <input type="text" name="name" placeholder="Task name" required
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="project_id" class="border border-gray-300 rounded-md px-3 py-2">
                <option value="">No Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}" {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Task
            </button>
        </form>
    </div>

    <!-- Tasks List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">
                Tasks
                @if($selectedProjectId)
                    - {{ $projects->find($selectedProjectId)->name ?? 'Unknown Project' }}
                @endif
            </h2>
            <p class="text-sm text-gray-600 mt-1">Drag and drop to reorder tasks</p>
        </div>

        @if($tasks->count() > 0)
            <ul id="task-list" class="divide-y divide-gray-200">
                @foreach($tasks as $task)
                    <li class="task-item p-4 hover:bg-gray-50 cursor-move" data-task-id="{{ $task->id }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                                        {{ $task->priority }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $task->name }}</p>
                                    @if($task->project)
                                        <p class="text-xs text-gray-500">{{ $task->project->name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">
                                        Created: {{ $task->created_at->format('M j, Y g:i A') }}
                                        @if($task->updated_at != $task->created_at)
                                            | Updated: {{ $task->updated_at->format('M j, Y g:i A') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tasks.edit', $task) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this task?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-8 text-center text-gray-500">
                <p>No tasks found. Create your first task above!</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const taskList = document.getElementById('task-list');

    if (taskList) {
        const sortable = Sortable.create(taskList, {
            animation: 150,
            ghostClass: 'bg-blue-50',
            onEnd: function(evt) {
                const taskIds = Array.from(taskList.children).map(item =>
                    item.getAttribute('data-task-id')
                );

                fetch('{{ route("tasks.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        task_ids: taskIds,
                        project_id: {{ $selectedProjectId ?? 'null' }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update priority numbers in the UI
                        taskList.querySelectorAll('.task-item').forEach((item, index) => {
                            const prioritySpan = item.querySelector('span');
                            prioritySpan.textContent = index + 1;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to reorder tasks. Please refresh the page.');
                });
            }
        });
    }
});
</script>
@endsection
