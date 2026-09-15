<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        // New tasks go to the bottom of their project's list.
        $nextPosition = Task::where('project_id', $validated['project_id'] ?? null)->max('position') + 1;

        Task::create([
            ...$validated,
            'position' => $nextPosition,
        ]);

        return redirect()
            ->back()
            ->with('status', 'Task created.');
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
        ]);

        // If the task is moving to a different project, put it at the end of the new list.
        if (array_key_exists('project_id', $validated) && $validated['project_id'] != $task->project_id) {
            $validated['position'] = Task::where('project_id', $validated['project_id'])->max('position') + 1;
        }

        $task->update($validated);

        return redirect()
            ->back()
            ->with('status', 'Task updated.');
    }

    public function toggleStatus(Task $task): RedirectResponse
    {
        $order = ['pending', 'in_progress', 'completed'];
        $next = $order[(array_search($task->status, $order) + 1) % count($order)];
        $task->update(['status' => $next]);

        return redirect()->back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->back()->with('status', 'Task deleted.');
    }

    /**
     * Persist a new drag-and-drop order for a set of tasks.
     * Expects JSON: { "order": [5, 2, 8, 1] } - an array of task IDs
     * in their new top-to-bottom order.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', 'exists:tasks,id'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['order'] as $index => $taskId) {
                Task::where('id', $taskId)->update(['position' => $index]);
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
