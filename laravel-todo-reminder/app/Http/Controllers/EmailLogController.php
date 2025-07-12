<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the email logs.
     */
    public function index()
    {
        if (request()->expectsJson()) {
            $emailLogs = EmailLog::orderBy('sent_at', 'desc')->get();
            return response()->json($emailLogs);
        }
        
        $emailLogs = EmailLog::orderBy('sent_at', 'desc')->paginate(20);
        return view('email-logs.index', compact('emailLogs'));
    }

    /**
     * Display the specified email log.
     */
    public function show(EmailLog $emailLog): JsonResponse
    {
        return response()->json($emailLog);
    }
}
