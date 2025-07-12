<?php

namespace App\Jobs;

use App\Models\Todo;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendTodoReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Todo $todo
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Fetch API data for CSV attachment
            $apiData = $this->fetchApiData();
            $csvContent = $this->generateCsvContent($apiData);
            $csvPath = $this->saveCsvFile($csvContent);

            // Send email with CSV attachment
            Mail::raw("Your todo '{$this->todo->title}' is due in 10 minutes!", function ($message) use ($csvPath) {
                $message->to($this->todo->recipient_email)
                        ->subject("Reminder: {$this->todo->title}")
                        ->attach($csvPath, [
                            'as' => 'api_data.csv',
                            'mime' => 'text/csv',
                        ]);
            });

            // Log successful email
            EmailLog::create([
                'to_email' => $this->todo->recipient_email,
                'subject' => "Reminder: {$this->todo->title}",
                'body' => "Your todo '{$this->todo->title}' is due in 10 minutes!",
                'status' => 'success',
                'sent_at' => now(),
            ]);

            // Mark todo as notification sent
            $this->todo->update(['email_notification_sent' => true]);

            // Clean up CSV file
            Storage::delete($csvPath);

        } catch (\Exception $e) {
            // Log failed email
            EmailLog::create([
                'to_email' => $this->todo->recipient_email,
                'subject' => "Reminder: {$this->todo->title}",
                'body' => "Failed to send reminder for todo '{$this->todo->title}'",
                'status' => 'failed',
                'sent_at' => now(),
            ]);

            throw $e;
        }
    }

    private function fetchApiData(): array
    {
        $response = Http::get('https://jsonplaceholder.typicode.com/posts');
        $posts = $response->json();
        
        return array_slice($posts, 0, 10);
    }

    private function generateCsvContent(array $data): string
    {
        $csv = "Title\n";
        foreach ($data as $post) {
            $csv .= "\"{$post['title']}\"\n";
        }
        return $csv;
    }

    private function saveCsvFile(string $content): string
    {
        $filename = 'api_data_' . time() . '.csv';
        $path = 'temp/' . $filename;
        Storage::put($path, $content);
        return Storage::path($path);
    }
}
