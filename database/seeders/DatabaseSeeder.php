<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Sample projects
        $project1 = Project::create([
            'name' => 'Website Redesign',
            'description' => 'Complete redesign of company website'
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App',
            'description' => 'Development of mobile application'
        ]);

        // Sample tasks
        Task::create([
            'name' => 'Design homepage mockup',
            'priority' => 1,
            'project_id' => $project1->id
        ]);

        Task::create([
            'name' => 'Implement responsive navigation',
            'priority' => 2,
            'project_id' => $project1->id
        ]);

        Task::create([
            'name' => 'Set up user authentication',
            'priority' => 1,
            'project_id' => $project2->id
        ]);

        Task::create([
            'name' => 'Create database schema',
            'priority' => 2,
            'project_id' => $project2->id
        ]);

        // A Task without project
        Task::create([
            'name' => 'General maintenance task',
            'priority' => 1,
            'project_id' => null
        ]);
    }
}
