@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
<div class="flex gap-6 items-start">

    {{-- ===================== SIDEBAR: PROJECTS ===================== --}}
    <aside class="w-64 shrink-0 bg-white rounded-xl border border-slate-200 p-4 sticky top-4">
        <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-3">Filter by project</h2>

        <nav class="space-y-1 mb-4">
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $activeProject === null ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-slate-50' }}">
                All Tasks
            </a>
            <a href="{{ route('dashboard', ['project' => 'none']) }}"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $activeProject === 'none' ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-slate-50' }}">
                No Project
            </a>

            @foreach ($projects as $project)
                <div class="group flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ (string) $activeProject === (string) $project->id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-slate-50' }}">
                    <a href="{{ route('dashboard', ['project' => $project->id]) }}" class="flex items-center gap-2 flex-1 truncate">
                        <span class="inline-block w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $project->color }}"></span>
                        <span class="truncate">{{ $project->name }}</span>
                        <span class="text-xs text-slate-400">({{ $project->tasks_count }})</span>
                    </a>
                    <details class="relative">
                        <summary class="list-none cursor-pointer text-slate-400 hover:text-slate-700 px-1">&#8942;</summary>
                        <div class="absolute right-0 z-10 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-lg p-3 space-y-2">
                            <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-1">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $project->name }}" required
                                       class="w-full text-xs border rounded px-2 py-1">
                                <input type="color" name="color" value="{{ $project->color }}" class="w-full h-6">
                                <button class="w-full text-xs bg-slate-800 text-white rounded py-1">Save</button>
                            </form>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                  onsubmit="return confirm('Delete this project? Its tasks will become unassigned.');">
                                @csrf
                                @method('DELETE')
                                <button class="w-full text-xs bg-red-50 text-red-700 rounded py-1">Delete project</button>
                            </form>
                        </div>
                    </details>
                </div>
            @endforeach
        </nav>

        <details>
            <summary class="cursor-pointer text-sm text-indigo-600 font-medium">+ New project</summary>
            <form action="{{ route('projects.store') }}" method="POST" class="mt-2 space-y-2">
                @csrf
                <input type="text" name="name" placeholder="Project name" required
                       class="w-full text-sm border rounded-lg px-3 py-1.5">
                <input type="color" name="color" value="#6366f1" class="w-full h-8">
                <button class="w-full text-sm bg-indigo-600 text-white rounded-lg py-1.5">Create project</button>
            </form>
        </details>
    </aside>

    {{-- ===================== MAIN: TASKS ===================== --}}
    <section class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-semibold">
                @if ($activeProject === 'none')
                    No Project
                @elseif ($activeProject)
                    {{ $projects->firstWhere('id', (int) $activeProject)?->name ?? 'Tasks' }}
                @else
                    All Tasks
                @endif
            </h1>
        </div>

        {{-- New task form --}}
        <details class="bg-white rounded-xl border border-slate-200 p-4 mb-4" {{ $tasks->isEmpty() ? 'open' : '' }}>
            <summary class="cursor-pointer text-sm font-medium text-indigo-600">+ Add a task</summary>
            <form action="{{ route('tasks.store') }}" method="POST" class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @csrf
                <input type="text" name="title" placeholder="Task title" required
                       class="sm:col-span-2 border rounded-lg px-3 py-2 text-sm">
                <textarea name="description" placeholder="Description (optional)" rows="2"
                          class="sm:col-span-2 border rounded-lg px-3 py-2 text-sm"></textarea>
                <select name="project_id" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">No project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                            @selected(is_numeric($activeProject) && (int) $activeProject === $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="due_date" class="border rounded-lg px-3 py-2 text-sm">
                <button class="sm:col-span-2 bg-indigo-600 text-white rounded-lg py-2 text-sm font-medium">
                    Add task
                </button>
            </form>
        </details>

        {{-- Task list (drag-and-drop reorderable) --}}
        @if ($tasks->isEmpty())
            <p class="text-sm text-slate-400 text-center py-10">No tasks here yet.</p>
        @else
            <ul id="task-list" class="space-y-2">
                @foreach ($tasks as $task)
                    <li data-id="{{ $task->id }}"
                        class="task-item bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-start gap-3 {{ $task->status === 'completed' ? 'opacity-60' : '' }}">

                        <span class="drag-handle cursor-grab text-slate-300 hover:text-slate-500 select-none pt-1">&#8942;&#8942;</span>

                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="pt-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="Cycle status: pending &rarr; in progress &rarr; completed"
                                    class="w-5 h-5 rounded-full border-2 flex items-center justify-center text-[10px]
                                        {{ match($task->status) {
                                            'completed' => 'bg-green-500 border-green-500 text-white',
                                            'in_progress' => 'border-amber-500 text-amber-500',
                                            default => 'border-slate-300 text-transparent',
                                        } }}">
                                &#10003;
                            </button>
                        </form>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium {{ $task->status === 'completed' ? 'line-through' : '' }}">
                                    {{ $task->title }}
                                </span>
                                <span class="text-[11px] px-2 py-0.5 rounded-full
                                    {{ match($task->status) {
                                        'completed' => 'bg-green-100 text-green-700',
                                        'in_progress' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-slate-100 text-slate-500',
                                    } }}">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                                @if ($task->project)
                                    <span class="text-[11px] px-2 py-0.5 rounded-full text-white"
                                          style="background-color: {{ $task->project->color }}">
                                        {{ $task->project->name }}
                                    </span>
                                @endif
                                @if ($task->due_date)
                                    <span class="text-[11px] text-slate-400">Due {{ $task->due_date->format('M j') }}</span>
                                @endif
                            </div>
                            @if ($task->description)
                                <p class="text-sm text-slate-500 mt-1">{{ $task->description }}</p>
                            @endif
                        </div>

                        <details class="relative">
                            <summary class="list-none cursor-pointer text-slate-400 hover:text-slate-700 px-1">&#8942;</summary>
                            <div class="absolute right-0 z-10 mt-1 w-64 bg-white border border-slate-200 rounded-lg shadow-lg p-3">
                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="title" value="{{ $task->title }}" required
                                           class="w-full text-sm border rounded px-2 py-1">
                                    <textarea name="description" rows="2"
                                              class="w-full text-sm border rounded px-2 py-1">{{ $task->description }}</textarea>
                                    <select name="project_id" class="w-full text-sm border rounded px-2 py-1">
                                        <option value="">No project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected($task->project_id === $project->id)>
                                                {{ $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}"
                                           class="w-full text-sm border rounded px-2 py-1">
                                    <button class="w-full text-sm bg-slate-800 text-white rounded py-1.5">Save changes</button>
                                </form>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="mt-2"
                                      onsubmit="return confirm('Delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full text-sm bg-red-50 text-red-700 rounded py-1.5">Delete task</button>
                                </form>
                            </div>
                        </details>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>

{{-- Drag-and-drop reordering --}}
<script>
    const taskList = document.getElementById('task-list');
    if (taskList) {
        new Sortable(taskList, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                const order = Array.from(taskList.querySelectorAll('.task-item')).map(el => el.dataset.id);
                fetch('{{ route('tasks.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ order }),
                });
            },
        });
    }
</script>
@endsection
