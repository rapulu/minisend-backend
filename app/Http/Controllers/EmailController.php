<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Resources\EmailLogCollection;
use App\Mail\Email;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{

    public function send(EmailRequest $request)
    {
        Mail::to($request->recipient)->send(new Email($request));
        return response()->json(['status' => true]);
    }

    public function search(EmailRequest $request)
    {
        $query = EmailLog::query();

        if($s = $request->input('s')) {
            $query->where('subject', 'LIKE', "%{$s}%")
                ->orWhere('from', 'LIKE', "%{$s}%")
                ->orWhere('to', 'LIKE', "%{$s}%");
        }

        return new EmailLogCollection($query->get());
    }

    public function find(EmailLog $email)
    {
        return response()->json([
            'data' => [
                'id' => $email->id,
                'from' => $email->from,
                'subject' => $email->subject,
                'body' => $email->body
            ]
        ]);
    }

}
