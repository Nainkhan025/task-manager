@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
<div class="flex flex-col md:flex-row gap-8 items-start">

    {{-- ===================== SIDEBAR: PROJECTS ===================== --}}
    <aside class="w-full md:w-60 shrink-0 bg-white/70 backdrop-blur-xs rounded-xl border border-zinc-200/80 p-4 sticky top-6 shadow-xs">
        <h2 class="text-[11px] font-medium uppercase tracking-wider text-zinc-400 mb-3 px-2">Projects</h2>

        <nav class="space-y-0.5 mb-4">
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-between px-2.5 py-1.5 rounded-md text-xs font-medium transition-all {{ $activeProject === null ? 'bg-zinc-200/70 text-zinc-900' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70' }}">
                <span>All Tasks</span>
            </a>
            <a href="{{ route('dashboard', ['project' => 'none']) }}"
               class="flex items-center justify-between px-2.5 py-1.5 rounded-md text-xs font-medium transition-all {{ $activeProject === 'none' ? 'bg-zinc-200/70 text-zinc-900' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70' }}">
                <span>No Project</span>
            </a>

            @foreach ($projects as $project)
                <div class="group flex items-center justify-between px-2.5 py-1.5 rounded-md text-xs transition-all {{ (string) $activeProject === (string) $project->id ? 'bg-zinc-200/70 text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/70' }}">
                    <a href="{{ route('dashboard', ['project' => $project->id]) }}" class="flex items-center gap-2 flex-1 truncate">
                        <span class="inline-block w-2 h-2 rounded-full shrink-0 opacity-80 group-hover:opacity-100 transition-opacity" style="background-color: {{ $project->color }}"></span>
                        <span class="truncate">{{ $project->name }}</span>
                        <span class="text-[11px] text-zinc-400 group-hover:text-zinc-500">({{ $project->tasks_count }})</span>
                    </a>
                    <details class="relative">
                        <summary class="list-none cursor-pointer text-zinc-400 hover:text-zinc-700 px-1 text-xs select-none rounded hover:bg-zinc-200/50 transition-colors">&#8942;</summary>
                        <div class="absolute right-0 z-20 mt-1 w-52 bg-white border border-zinc-200 rounded-lg shadow-xl shadow-zinc-900/5 p-3 space-y-2">
                            <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $project->name }}" required
                                       class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors">
                                <div class="flex items-center gap-2">
                                    <label class="text-[11px] text-zinc-500">Color</label>
                                    <input type="color" name="color" value="{{ $project->color }}" class="w-full h-7 rounded border border-zinc-200 cursor-pointer p-0.5">
                                </div>
                                <button class="w-full text-xs bg-zinc-900 text-white rounded-md py-1.5 hover:bg-zinc-800 transition-colors font-medium">Save</button>
                            </form>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                  data-confirm-title="Delete project &ldquo;{{ $project->name }}&rdquo;?"
                                  data-confirm-message="Delete this project? Its tasks will become unassigned.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-xs text-red-600 hover:bg-red-50 rounded-md py-1.5 transition-colors font-medium text-left px-2">Delete project</button>
                            </form>
                        </div>
                    </details>
                </div>
            @endforeach
        </nav>

        <details class="mt-4 pt-3 border-t border-zinc-100">
            <summary class="cursor-pointer text-xs font-medium text-zinc-500 hover:text-zinc-900 transition-colors flex items-center gap-1.5 px-2 py-1 select-none">
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>New project</span>
            </summary>
            <form action="{{ route('projects.store') }}" method="POST" class="mt-2.5 space-y-2">
                @csrf
                <input type="text" name="name" placeholder="Project name" required
                       class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors placeholder:text-zinc-400">
                <div class="flex items-center gap-2">
                    <label class="text-[11px] text-zinc-500 shrink-0">Color</label>
                    <input type="color" name="color" value="#6366f1" class="w-full h-7 rounded border border-zinc-200 cursor-pointer p-0.5">
                </div>
                <button class="w-full text-xs bg-zinc-900 text-white rounded-md py-1.5 hover:bg-zinc-800 transition-colors font-medium">Create project</button>
            </form>
        </details>
    </aside>

    {{-- ===================== MAIN: TASKS ===================== --}}
    <section class="flex-1 min-w-0 w-full">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-lg font-semibold tracking-tight text-zinc-900 flex items-center gap-2">
                @if ($activeProject === 'none')
                    <span>No Project</span>
                @elseif ($activeProject)
                    <span>{{ $projects->firstWhere('id', (int) $activeProject)?->name ?? 'Tasks' }}</span>
                @else
                    <span>All Tasks</span>
                @endif
            </h1>
        </div>

        {{-- New task form --}}
        <details class="bg-white rounded-xl border border-zinc-200/80 p-4 mb-6 shadow-xs transition-all" {{ $tasks->isEmpty() ? 'open' : '' }}>
            <summary class="cursor-pointer text-xs font-medium text-zinc-600 hover:text-zinc-900 transition-colors flex items-center gap-1.5 select-none">
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add a task</span>
            </summary>
            <form action="{{ route('tasks.store') }}" method="POST" class="mt-3.5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @csrf
                <input type="text" name="title" placeholder="Task title" required
                       class="sm:col-span-2 border border-zinc-200 rounded-lg px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 focus:ring-1 focus:ring-zinc-400 transition-all placeholder:text-zinc-400">
                <textarea name="description" placeholder="Description (optional)" rows="2"
                          class="sm:col-span-2 border border-zinc-200 rounded-lg px-3 py-2 text-xs text-zinc-900 focus:outline-none focus:border-zinc-400 focus:ring-1 focus:ring-zinc-400 transition-all placeholder:text-zinc-400"></textarea>
                <select name="project_id" class="border border-zinc-200 rounded-lg px-3 py-2 text-xs text-zinc-700 focus:outline-none focus:border-zinc-400 transition-all bg-white">
                    <option value="">No project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}"
                            @selected(is_numeric($activeProject) && (int) $activeProject === $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="due_date" class="border border-zinc-200 rounded-lg px-3 py-2 text-xs text-zinc-700 focus:outline-none focus:border-zinc-400 transition-all bg-white">
                <button class="sm:col-span-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg py-2 text-xs font-medium transition-colors">
                    Add task
                </button>
            </form>
        </details>

        {{-- Task list (drag-and-drop reorderable) --}}
        @if ($tasks->isEmpty())
            <div class="border border-dashed border-zinc-200 rounded-xl p-12 text-center bg-white/40">
                <p class="text-xs text-zinc-400 font-normal">No tasks here yet.</p>
            </div>
        @else
            <ul id="task-list" class="space-y-2">
                @foreach ($tasks as $task)
                    <li data-id="{{ $task->id }}"
                        class="task-item group bg-white border border-zinc-200/90 hover:border-zinc-300 rounded-xl px-4 py-3.5 flex items-start gap-3.5 transition-all shadow-2xs {{ $task->status === 'completed' ? 'bg-zinc-50/60' : '' }}">

                        {{-- Drag handle --}}
                        <span class="drag-handle cursor-grab text-zinc-300 group-hover:text-zinc-400 hover:!text-zinc-700 select-none pt-1 transition-colors" title="Drag to reorder">
                            <svg class="w-4 h-4" viewBox="0 0 16 16" fill="currentColor">
                                <circle cx="5" cy="3" r="1"/>
                                <circle cx="11" cy="3" r="1"/>
                                <circle cx="5" cy="8" r="1"/>
                                <circle cx="11" cy="8" r="1"/>
                                <circle cx="5" cy="13" r="1"/>
                                <circle cx="11" cy="13" r="1"/>
                            </svg>
                        </span>

                        {{-- Toggle Status --}}
                        <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="pt-0.5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="Cycle status: pending &rarr; in progress &rarr; completed"
                                    class="w-4 h-4 rounded-full border flex items-center justify-center text-[9px] transition-all
                                        {{ match($task->status) {
                                            'completed' => 'bg-zinc-900 border-zinc-900 text-white shadow-xs',
                                            'in_progress' => 'border-amber-500 text-amber-500 bg-amber-50/40',
                                            default => 'border-zinc-300 hover:border-zinc-400 text-transparent',
                                        } }}">
                                &#10003;
                            </button>
                        </form>

                        {{-- Task Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-xs tracking-tight {{ $task->status === 'completed' ? 'line-through text-zinc-400' : 'text-zinc-900' }}">
                                    {{ $task->title }}
                                </span>

                                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full border
                                    {{ match($task->status) {
                                        'completed' => 'bg-zinc-100 text-zinc-500 border-zinc-200/60',
                                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                        default => 'bg-zinc-100 text-zinc-600 border-zinc-200/60',
                                    } }}">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>

                                @if ($task->project)
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-medium px-2 py-0.5 rounded-full bg-zinc-50 border border-zinc-200/80 text-zinc-700">
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $task->project->color }}"></span>
                                        <span>{{ $task->project->name }}</span>
                                    </span>
                                @endif

                                @if ($task->due_date)
                                    <span class="text-[11px] text-zinc-400 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Due {{ $task->due_date->format('M j') }}
                                    </span>
                                @endif
                            </div>
                            @if ($task->description)
                                <p class="text-xs text-zinc-500 mt-1 leading-relaxed">{{ $task->description }}</p>
                            @endif
                        </div>

                        {{-- Task Action Menu --}}
                        <details class="relative">
                            <summary class="list-none cursor-pointer text-zinc-300 group-hover:text-zinc-400 hover:!text-zinc-700 px-1 py-0.5 rounded text-xs transition-colors select-none">&#8942;</summary>
                            <div class="absolute right-0 z-20 mt-1 w-64 bg-white border border-zinc-200 rounded-xl shadow-xl shadow-zinc-900/5 p-3.5 space-y-2.5">
                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-2">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="text-[10px] uppercase font-semibold text-zinc-400">Title</label>
                                        <input type="text" name="title" value="{{ $task->title }}" required
                                               class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5">
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-semibold text-zinc-400">Description</label>
                                        <textarea name="description" rows="2"
                                                  class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5">{{ $task->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-semibold text-zinc-400">Project</label>
                                        <select name="project_id" class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5 bg-white">
                                            <option value="">No project</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}" @selected($task->project_id === $project->id)>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-semibold text-zinc-400">Due Date</label>
                                        <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}"
                                               class="w-full text-xs border border-zinc-200 rounded-md px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5 bg-white">
                                    </div>
                                    <button class="w-full text-xs bg-zinc-900 text-white rounded-md py-1.5 hover:bg-zinc-800 transition-colors font-medium mt-1">Save changes</button>
                                </form>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="pt-1 border-t border-zinc-100"
                                      data-confirm-title="Delete task?"
                                      data-confirm-message="Are you sure you want to delete this task? This action cannot be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-xs text-red-600 hover:bg-red-50 rounded-md py-1.5 transition-colors font-medium text-left px-2">Delete task</button>
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
