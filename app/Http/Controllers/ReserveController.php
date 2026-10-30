<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionReserve;
use App\Http\Requests\StoreReserveRequest;
use Illuminate\Support\Facades\Auth;

class ReserveController extends Controller
{
    public function store(StoreReserveRequest $request)
    {
        // チェック済みの入力データを取得
        $validated = $request->validated();

        // 保存処理
        // 予約データがあれば更新、なければ新しく作る
        // 第一引数が検索条件、第二引数が登録するデータの中身
        ActionReserve::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'is_processed' => false,
            ],
            [
                'location_id' => $validated['location_id'],
                'stance_id' => $validated['stance_id'],
                'is_processed' => false,
            ]
        );

        return redirect()->route('dashboard')->with('status', '行動を予約しました!');
    }
}
