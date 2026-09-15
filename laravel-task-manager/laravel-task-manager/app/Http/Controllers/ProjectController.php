<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        $project = Project::create($validated);

        return redirect()
            ->route('dashboard', ['project' => $project->id])
            ->with('status', "Project \"{$project->name}\" created.");
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        $project->update($validated);

        return redirect()
            ->route('dashboard', ['project' => $project->id])
            ->with('status', "Project \"{$project->name}\" updated.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        $name = $project->name;
        // Tasks are kept but unassigned (see nullOnDelete on the migration).
        $project->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', "Project \"{$name}\" deleted. Its tasks were moved to \"No Project\".");
    }
}
