<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.minisend')
                    ->text('mail.minisendplain')
                    ->from($this->data['sender'])
                    ->to($this->data['recipient'])
                    ->subject($this->data['subject'])
                    ->attach($this->data['file'])
                    ->with([
                        'body' => $this->data['body'],
                        'name' => $this->data['name']
                    ]);
    }
}
