@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
    </div>

    <!-- Add Project Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold mb-4">Add New Project</h2>
        <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="name" placeholder="Project name" required
                       class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="description" placeholder="Description (optional)"
                       class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Add Project
            </button>
        </form>
    </div>

    <!-- Projects List -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">All Projects</h2>
        </div>

        @if($projects->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($projects as $project)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $project->name }}</h3>
                                @if($project->description)
                                    <p class="text-sm text-gray-600">{{ $project->description }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm">View Tasks</a>
                                <a href="{{ route('projects.edit', $project) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                <form method="POST" action="{{ route('projects.destroy', $project) }}"
                                      onsubmit="return confirm('Are you sure? This will delete all associated tasks.')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-gray-500">
                <p>No projects found. Create your first project above!</p>
            </div>
        @endif
    </div>
</div>
@endsection
