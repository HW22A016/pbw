<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        // ログイン中のユーザー(プレイヤー)ログを取得
        $logs = Log::where('user_id', Auth::id())->latest()->get();
        return view('logs', compact('logs'));
    }
}
