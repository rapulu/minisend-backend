<?php

namespace App\Services;

use App\Models\EmailLog;
use Carbon\Carbon;
use Illuminate\Mail\Events\MessageSending;
use Symfony\Component\Mime\Email;

class EmailLogger {

    public function handle(MessageSending $event):void
    {
        $email = $event->message;
        $log = new EmailLog();

        $log->from = $this->formatHeader($email, 'From');
        $log->to = $this->formatHeader($email, 'To');
        $log->subject = $email->getSubject();
        $log->body = $email->getBody()->bodyToString();
        $log->attachments = '';
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
