<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // ログイン中のユーザー(プレイヤー)情報を取得
        $user = Auth::user();

        $character = $user->character;

        $currentReserve = $user->actionReserves()->first();

        return view('dashboard', compact('user', 'character', 'currentReserve'));
    }
}
