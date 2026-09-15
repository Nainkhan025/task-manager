<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the main task board, optionally filtered by project.
     *
     * Query params:
     *  - project=<id>   only tasks belonging to that project
     *  - project=none   only tasks with no project assigned
     *  (no param)       all tasks
     */
    public function index(Request $request): View
    {
        $projects = Project::withCount('tasks')->orderBy('name')->get();

        $activeProject = $request->query('project');

        $tasksQuery = Task::query()->with('project')->orderBy('position');

        if ($activeProject === 'none') {
            $tasksQuery->whereNull('project_id');
        } elseif ($activeProject !== null && $activeProject !== '') {
            $tasksQuery->where('project_id', $activeProject);
        }

        $tasks = $tasksQuery->get();

        return view('dashboard.index', [
            'projects' => $projects,
            'tasks' => $tasks,
            'activeProject' => $activeProject,
        ]);
    }
}
