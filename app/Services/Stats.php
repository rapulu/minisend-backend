<?php

namespace App\Services;

use App\Models\EmailLog;

class Stats {

    public static function getEmailStats() :array
    {
        return [
            'total_email' => EmailLog::count(),
            'total_email_sent' => EmailLog::where('status', true)->count(),
            'total_email_failed' => EmailLog::where('status', false)->count()
        ];
    }
}
