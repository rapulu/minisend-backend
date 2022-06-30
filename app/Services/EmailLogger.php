<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\EmailLog;
use Symfony\Component\Mime\Email;
use Illuminate\Mail\Events\MessageSending;

class EmailLogger {

    public function handle(MessageSending $event):void
    {
        $email = $event->message;
        $log = new EmailLog();

        $log->sender = $this->formatHeader($email, 'From');
        $log->recipient = $this->formatHeader($email, 'To');
        $log->subject = $email->getSubject();
        $log->body = $email->getTextBody();
        $log->status = false;
        $log->date = Carbon::now()->format('Y-m-d H:i:s');
        $log->save();
    }

    private function formatHeader(Email $message, string $field): ?string
    {
        $headers = $message->getHeaders();

        return $headers->get($field)?->getBodyAsString();
    }
}
