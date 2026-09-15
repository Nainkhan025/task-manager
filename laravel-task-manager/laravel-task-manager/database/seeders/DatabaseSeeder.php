<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $website = Project::create([
            'name' => 'Website Redesign',
            'description' => 'Refresh the marketing site.',
            'color' => '#6366f1',
        ]);

        $mobile = Project::create([
            'name' => 'Mobile App',
            'description' => 'v2.0 of the iOS/Android app.',
            'color' => '#10b981',
        ]);

        $tasksByProject = [
            $website->id => [
                ['title' => 'Wireframe the homepage', 'status' => 'completed'],
                ['title' => 'Pick a color palette', 'status' => 'in_progress'],
                ['title' => 'Write new copy for the About page', 'status' => 'pending'],
            ],
            $mobile->id => [
                ['title' => 'Design onboarding flow', 'status' => 'pending'],
                ['title' => 'Set up push notifications', 'status' => 'pending'],
            ],
        ];

        foreach ($tasksByProject as $projectId => $tasks) {
            foreach ($tasks as $position => $task) {
                Task::create([
                    'project_id' => $projectId,
                    'title' => $task['title'],
                    'status' => $task['status'],
                    'position' => $position,
                ]);
            }
        }

        Task::create([
            'project_id' => null,
            'title' => 'Renew domain registration',
            'status' => 'pending',
            'position' => 0,
        ]);
    }
}
