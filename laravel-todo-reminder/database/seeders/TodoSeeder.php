<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample todos for testing
        $todos = [
            [
                'title' => 'Complete Project Documentation',
                'description' => 'Finish writing the technical documentation for the Laravel Todo application',
                'due_datetime' => now()->addMinutes(15), // Will trigger reminder in 5 minutes
                'email_notification_sent' => false,
            ],
            [
                'title' => 'Review Code Changes',
                'description' => 'Review and approve the latest pull requests',
                'due_datetime' => now()->addHours(2),
                'email_notification_sent' => false,
            ],
            [
                'title' => 'Team Meeting',
                'description' => 'Weekly team standup meeting',
                'due_datetime' => now()->addDays(1),
                'email_notification_sent' => false,
            ],
            [
                'title' => 'Deploy to Production',
                'description' => 'Deploy the latest version to production environment',
                'due_datetime' => now()->addDays(3),
                'email_notification_sent' => false,
            ],
            [
                'title' => 'Test Email Reminder System',
                'description' => 'Test the email reminder functionality with a todo due in 10 minutes',
                'due_datetime' => now()->addMinutes(10), // Will trigger reminder immediately
                'email_notification_sent' => false,
            ],
        ];

        foreach ($todos as $todoData) {
            Todo::create($todoData);
        }

        $this->command->info('Sample todos created successfully!');
        $this->command->info('You can test the email reminder system with: php artisan todos:test-reminder');
    }
}
