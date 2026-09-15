@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
<div class="flex flex-col md:flex-row w-full min-h-screen">

    {{-- ===================== SIDEBAR: PROJECTS ===================== --}}
    <aside class="w-full md:w-64 shrink-0 bg-white border-b md:border-b-0 md:border-r border-zinc-200/80 md:h-screen md:sticky md:top-0 flex flex-col justify-between z-20 shadow-xs">
        
        {{-- Header / Brand --}}
        <div class="h-16 px-5 flex items-center justify-between border-b border-zinc-100 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white shadow-xs">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-semibold text-zinc-900 leading-tight tracking-tight">Task Manager</h1>
                    <p class="text-[10px] text-zinc-400 font-medium">Workspace</p>
                </div>
            </div>
        </div>

        {{-- Scrollable Nav Section --}}
        <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-5">
            <div>
                <div class="flex items-center justify-between px-2.5 mb-2">
                    <h2 class="text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Projects</h2>
                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-zinc-100 text-zinc-500">{{ count($projects) }}</span>
                </div>

                <nav class="space-y-1">
                    {{-- All Tasks --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all group {{ $activeProject === null ? 'bg-zinc-900 text-white shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/80' }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 transition-colors {{ $activeProject === null ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            <span class="truncate">All Tasks</span>
                        </div>
                    </a>

                    {{-- No Project --}}
                    <a href="{{ route('dashboard', ['project' => 'none']) }}"
                       class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all group {{ $activeProject === 'none' ? 'bg-zinc-900 text-white shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/80' }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg class="w-4 h-4 shrink-0 transition-colors {{ $activeProject === 'none' ? 'text-white' : 'text-zinc-400 group-hover:text-zinc-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <span class="truncate">No Project</span>
                        </div>
                    </a>

                    {{-- Dynamic Projects List --}}
                    @foreach ($projects as $project)
                        <div class="group flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-all {{ (string) $activeProject === (string) $project->id ? 'bg-zinc-900 text-white shadow-xs font-semibold' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100/80' }}">
                            <a href="{{ route('dashboard', ['project' => $project->id]) }}" class="flex items-center gap-2.5 flex-1 min-w-0 mr-1">
                                <span class="inline-block w-2.5 h-2.5 rounded-full shrink-0 shadow-2xs transition-transform group-hover:scale-110" style="background-color: {{ $project->color }}"></span>
                                <span class="truncate">{{ $project->name }}</span>
                                <span class="text-[10px] ml-auto shrink-0 px-1.5 py-0.5 rounded-full {{ (string) $activeProject === (string) $project->id ? 'bg-zinc-800 text-zinc-300' : 'text-zinc-400 bg-zinc-100' }}">
                                    {{ $project->tasks_count }}
                                </span>
                            </a>
                            <details class="relative shrink-0">
                                <summary class="list-none cursor-pointer text-zinc-400 hover:text-zinc-700 p-1 rounded-md hover:bg-zinc-200/50 transition-colors flex items-center justify-center select-none" title="Project options">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </summary>
                                <div class="absolute right-0 z-30 mt-1.5 w-52 bg-white border border-zinc-200 rounded-xl shadow-xl shadow-zinc-900/10 p-3 space-y-2.5 text-zinc-900 font-normal">
                                    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="text-[10px] uppercase font-semibold text-zinc-400">Name</label>
                                            <input type="text" name="name" value="{{ $project->name }}" required
                                                   class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5">
                                        </div>
                                        <div class="flex items-center justify-between gap-2">
                                            <label class="text-[11px] text-zinc-500">Color</label>
                                            <input type="color" name="color" value="{{ $project->color }}" class="w-8 h-7 rounded border border-zinc-200 cursor-pointer p-0.5">
                                        </div>
                                        <button class="w-full text-xs bg-zinc-900 text-white rounded-lg py-1.5 hover:bg-zinc-800 transition-colors font-medium">Save</button>
                                    </form>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="pt-1 border-t border-zinc-100"
                                          data-confirm-title="Delete project &ldquo;{{ $project->name }}&rdquo;?"
                                          data-confirm-message="Delete this project? Its tasks will become unassigned.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-xs text-red-600 hover:bg-red-50 rounded-lg py-1.5 transition-colors font-medium text-left px-2">Delete project</button>
                                    </form>
                                </div>
                            </details>
                        </div>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Footer Section --}}
        <div class="p-3.5 border-t border-zinc-100 bg-zinc-50/50 space-y-3 shrink-0">
            <details class="group">
                <summary class="cursor-pointer text-xs font-medium text-zinc-700 hover:text-zinc-900 bg-white hover:bg-zinc-100/80 border border-zinc-200/80 rounded-lg px-3 py-2 transition-all flex items-center justify-between select-none shadow-2xs">
                    <span class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New project</span>
                    </span>
                    <svg class="w-3.5 h-3.5 text-zinc-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <form action="{{ route('projects.store') }}" method="POST" class="mt-2 p-3 bg-white rounded-xl border border-zinc-200/80 space-y-2.5 shadow-xs">
                    @csrf
                    <div>
                        <label class="text-[10px] uppercase font-semibold text-zinc-400">Project Name</label>
                        <input type="text" name="name" placeholder="e.g. Website Redesign" required
                               class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5 placeholder:text-zinc-400">
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <label class="text-[11px] text-zinc-500">Color</label>
                        <input type="color" name="color" value="#6366f1" class="w-8 h-7 rounded border border-zinc-200 cursor-pointer p-0.5">
                    </div>
                    <button class="w-full text-xs bg-zinc-900 text-white rounded-lg py-1.5 hover:bg-zinc-800 transition-colors font-medium shadow-2xs">Create project</button>
                </form>
            </details>
        </div>
    </aside>

    {{-- ===================== MAIN: TASKS ===================== --}}
    <main class="flex-1 min-w-0 flex flex-col bg-[#fafafa]">
        
        {{-- Sticky Top Bar / View Title --}}
        <header class="h-16 px-6 lg:px-10 border-b border-zinc-200/80 bg-white/70 backdrop-blur-md sticky top-0 z-10 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <h1 class="text-base font-semibold tracking-tight text-zinc-900 flex items-center gap-2">
                    @if ($activeProject === 'none')
                        <span>No Project</span>
                    @elseif ($activeProject)
                        @php $currentProj = $projects->firstWhere('id', (int) $activeProject); @endphp
                        @if ($currentProj)
                            <span class="w-2.5 h-2.5 rounded-full inline-block shadow-2xs" style="background-color: {{ $currentProj->color }}"></span>
                            <span>{{ $currentProj->name }}</span>
                        @else
                            <span>Tasks</span>
                        @endif
                    @else
                        <span>All Tasks</span>
                    @endif
                </h1>
                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-600 border border-zinc-200/60">
                    {{ $tasks->count() }} {{ Str::plural('task', $tasks->count()) }}
                </span>
            </div>
        </header>

        {{-- Main Scrollable Viewport Content --}}
        <div class="flex-1 px-4 py-6 sm:px-8 sm:py-8 lg:px-10 max-w-5xl w-full mx-auto space-y-6">

            {{-- New Task Collapsible Card --}}
            <details class="group bg-white rounded-2xl border border-zinc-200/80 shadow-2xs transition-all overflow-hidden" {{ $tasks->isEmpty() ? 'open' : '' }}>
                <summary class="cursor-pointer text-xs font-semibold text-zinc-800 hover:text-zinc-900 px-5 py-4 flex items-center justify-between select-none bg-white transition-colors">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-md bg-zinc-100 text-zinc-600 flex items-center justify-center border border-zinc-200/60">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span>Add a new task</span>
                    </div>
                    <svg class="w-4 h-4 text-zinc-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </summary>
                <div class="px-5 pb-5 pt-1 border-t border-zinc-100 bg-zinc-50/40">
                    <form action="{{ route('tasks.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        @csrf
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] uppercase font-semibold text-zinc-400 mb-1">Title</label>
                            <input type="text" name="title" placeholder="What needs to be done?" required
                                   class="w-full border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all placeholder:text-zinc-400 bg-white">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] uppercase font-semibold text-zinc-400 mb-1">Description (optional)</label>
                            <textarea name="description" placeholder="Add more context or details..." rows="2"
                                      class="w-full border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 focus:outline-none focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 transition-all placeholder:text-zinc-400 bg-white"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-semibold text-zinc-400 mb-1">Project</label>
                            <select name="project_id" class="w-full border border-zinc-200 rounded-xl px-3.5 py-2 text-xs text-zinc-700 focus:outline-none focus:border-zinc-900 transition-all bg-white">
                                <option value="">No project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        @selected(is_numeric($activeProject) && (int) $activeProject === $project->id)>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-semibold text-zinc-400 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full border border-zinc-200 rounded-xl px-3.5 py-2 text-xs text-zinc-700 focus:outline-none focus:border-zinc-900 transition-all bg-white">
                        </div>
                        <button class="sm:col-span-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-xl py-2.5 text-xs font-semibold shadow-xs transition-all flex items-center justify-center gap-2 mt-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add task</span>
                        </button>
                    </form>
                </div>
            </details>

            {{-- Task List --}}
            @if ($tasks->isEmpty())
                <div class="border border-dashed border-zinc-200 rounded-2xl p-12 text-center bg-white/60 shadow-2xs">
                    <div class="w-12 h-12 rounded-2xl bg-zinc-100 text-zinc-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-semibold text-zinc-700">No tasks found</h3>
                    <p class="text-xs text-zinc-400 font-normal mt-1">Create your first task above to get started.</p>
                </div>
            @else
                <ul id="task-list" class="space-y-2.5">
                    @foreach ($tasks as $task)
                        <li data-id="{{ $task->id }}"
                            class="task-item group bg-white border border-zinc-200/90 hover:border-zinc-300 rounded-2xl px-4 py-3.5 sm:px-5 sm:py-4 flex items-start gap-3.5 transition-all shadow-2xs hover:shadow-xs {{ $task->status === 'completed' ? 'bg-zinc-50/50' : '' }}">

                            {{-- Drag handle --}}
                            <span class="drag-handle cursor-grab text-zinc-300 group-hover:text-zinc-400 hover:!text-zinc-700 select-none pt-0.5 transition-colors shrink-0" title="Drag to reorder">
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
                            <details class="relative shrink-0">
                                <summary class="list-none cursor-pointer text-zinc-400 hover:text-zinc-700 p-1 rounded-md hover:bg-zinc-100 transition-colors flex items-center justify-center select-none" title="Task options">&#8942;</summary>
                                <div class="absolute right-0 z-30 mt-1.5 w-64 bg-white border border-zinc-200 rounded-2xl shadow-xl shadow-zinc-900/10 p-3.5 space-y-2.5">
                                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="text-[10px] uppercase font-semibold text-zinc-400">Title</label>
                                            <input type="text" name="title" value="{{ $task->title }}" required
                                                   class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5">
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-semibold text-zinc-400">Description</label>
                                            <textarea name="description" rows="2"
                                                      class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5">{{ $task->description }}</textarea>
                                        </div>
                                        <div>
                                            <label class="text-[10px] uppercase font-semibold text-zinc-400">Project</label>
                                            <select name="project_id" class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5 bg-white">
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
                                                   class="w-full text-xs border border-zinc-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-zinc-400 transition-colors mt-0.5 bg-white">
                                        </div>
                                        <button class="w-full text-xs bg-zinc-900 text-white rounded-lg py-1.5 hover:bg-zinc-800 transition-colors font-medium mt-1">Save changes</button>
                                    </form>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="pt-1 border-t border-zinc-100"
                                          data-confirm-title="Delete task?"
                                          data-confirm-message="Are you sure you want to delete this task? This action cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-xs text-red-600 hover:bg-red-50 rounded-lg py-1.5 transition-colors font-medium text-left px-2">Delete task</button>
                                    </form>
                                </div>
                            </details>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </main>
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
