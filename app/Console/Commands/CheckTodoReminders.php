<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckTodoReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todos:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for todos that need reminder emails and queue them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingTodos = \App\Models\Todo::pendingReminders()->get();

        if ($pendingTodos->isEmpty()) {
            $this->info('No pending reminders found.');
            return;
        }

        $this->info("Found {$pendingTodos->count()} todos that need reminder emails.");

        foreach ($pendingTodos as $todo) {
            \App\Jobs\SendTodoReminderEmail::dispatch($todo);
            $this->line("Queued reminder email for todo: {$todo->title}");
        }

        $this->info('All reminder emails have been queued successfully.');
    }
}
