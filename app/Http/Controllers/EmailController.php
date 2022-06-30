<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Resources\EmailLogCollection;
use App\Mail\Email;
use App\Models\EmailLog;
use App\Services\Stats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class EmailController extends Controller
{

    public function send(EmailRequest $request)
    {
        try {
            Mail::send(new Email($request->validated()));
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            return response()->json(['err_message' => $e->getMessage()]);
        }

    }

    public function search(EmailRequest $request)
    {
        try {

            $query = EmailLog::query();

            if($s = $request->input('s')){
                $query->whereRaw("subject LIKE '%" .$s. "%'")
                        ->orWhereRaw("sender LIKE '%" .$s. "%'")
                        ->orWhereRaw("recipient LIKE '%" .$s. "%'");
            }

            $perPage = 1;
            $page = $request->input('page', 1);
            $total = $query->count();
            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            return [
                'data' => $result,
                'total' => $total,
                'page' => $page,
                'last_page' => ceil($total/$perPage)
            ];
        } catch (\Exception $e) {
            return response()->json(['err_message' => $e->getMessage()]);
        }
    }

    public function find(EmailLog $email)
    {
        return response()->json([
            'data' => [
                'id' => $email->id,
                'from' => $email->sender,
                'subject' => $email->subject,
                'body' => $email->body,
                'status' => $email->status ? 'Sent': 'Failed'
            ]
        ]);
    }

    public function getStats()
    {
        try {
            $stats = Stats::getEmailStats();
        } catch (\Exception $e) {
            return response()->json(['err_message' => $e->getMessage()]);
        }

        return response()->json([
            'data' => $stats
        ]);
    }

}
