<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_datetime',
        'recipient_email',
        'email_notification_sent',
    ];

    protected $casts = [
        'due_datetime' => 'datetime',
        'email_notification_sent' => 'boolean',
    ];

    public function scopePendingReminders($query)
    {
        return $query->where('email_notification_sent', false)
                    ->where('due_datetime', '>', now())
                    ->where('due_datetime', '<=', now()->addMinutes(10));
    }
}
