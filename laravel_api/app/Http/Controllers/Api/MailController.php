<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 同期
// use Illuminate\Support\Facades\Mail;
// use App\Mail\TestMail;

// 非同期
use App\Jobs\SendMailJob;

class MailController extends Controller
{
    public function index(){
        // 同期
        // Mail::to('test@example.com')->send(new TestMail());

        // 非同期
        SendMailJob::dispatch();
        
        var_dump('メール送信しました');
    }
}
