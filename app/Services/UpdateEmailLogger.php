<?php

namespace App\Services;

use App\Models\EmailLog;
use Illuminate\Mail\Events\MessageSent;

class UpdateEmailLogger {

    public function handle(MessageSent $event):void
    {
        $log = EmailLog::latest('id')->first();
        $log->status = true;
        $log->save();
    }
}
