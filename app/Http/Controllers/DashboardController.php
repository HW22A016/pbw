<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ログイン中のユーザー(プレイヤー)情報を取得
        $user = Auth::user();

        $character = $user->character;

        $currentReserve = $user->actionReserves()->where('is_processed', false)->first();

        // yyyy-mm-dd hh:mm:ss->23:59:59.9999...->YYYY-MM-DDTHH:mm:ss+TZ
        $nextUpdateTime = Carbon::now()->ceilDay()->toIso8601String();

        return view('dashboard', compact('user', 'character', 'currentReserve', 'nextUpdateTime'));
    }
}
