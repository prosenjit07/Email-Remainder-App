<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Jobs\SendTodoReminderEmail;
use Illuminate\Console\Command;

class TestEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todos:test-reminder {todo_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email reminder for a specific todo or all pending todos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todoId = $this->argument('todo_id');

        if ($todoId) {
            $todo = Todo::find($todoId);
            if (!$todo) {
                $this->error("Todo with ID {$todoId} not found.");
                return;
            }

            $this->info("Testing reminder for todo: {$todo->title}");
            SendTodoReminderEmail::dispatch($todo);
            $this->info("Reminder job dispatched for todo: {$todo->title}");
        } else {
            $pendingTodos = Todo::pendingReminders()->get();

            if ($pendingTodos->isEmpty()) {
                $this->info('No pending reminders found.');
                return;
            }

            $this->info("Found {$pendingTodos->count()} todos that need reminder emails.");

            foreach ($pendingTodos as $todo) {
                $this->line("Dispatching reminder for todo: {$todo->title}");
                SendTodoReminderEmail::dispatch($todo);
            }

            $this->info('All reminder emails have been dispatched.');
        }
    }
}
