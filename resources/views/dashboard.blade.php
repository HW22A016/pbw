<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('メイン画面') }}
        </h2>
    </x-slot>

    <!-- ステータス画面 -->
    <div class="py-12">
        <!-- max-w-7xl:画面の最大横幅を約1280ピクセルに制限 -->
         <!-- sm:px-6:タブレット用 -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 背景、ダークモード時の背景、要素からはみ出したら非表示、ドロップシャドウ、角をまるめる、上下左右の余白24px -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- 文字を18px, 太字, テキストの色, ダークモード時のテキストの色, 外側余白16px -->
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">プレイヤー情報</h3>
                <!-- グリッドレイアウト, 横に2列に並べる,  タブレットやPCの768pxは横五列, 要素の隙間16px-->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <!-- 背景色:明るいグレー, ダークモード時の背景色:濃いグレー, 内側の余白12px, 角を丸める4px, 中央寄せ -->
                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">名前</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $user->name }}</div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">Lv</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->level }}</div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">HP</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->hp }}</div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">攻撃力</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->atk }}</div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">所持金</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->money }}G</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 行動予約フォーム -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">次の定期更新の行動予約</h3>

            <!-- 現在の予約状況 -->
            @if($currentReserve->is_processed)
                <!-- マージン下に24pxの余白, パディング16px -->
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-900 text-green-800 dark:text-green-200 rounded">
                    現在の予約: <strong>探索先[{{ $currentReserve->location_id->label() }}]</strong> / <strong>戦闘方針[{{ $currentReserve->stance_id->label() }}]</strong>
                </div>
            @else
                <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-900 text-amber-800 dark:text-amber-200 rounded">
                    現在、行動が予約されていません
                </div>
            @endif

            <!-- 送信フォーム -->
             <!-- 子要素同士の上下の間隔をまとめて開ける16px -->
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <!-- ブロック要素に変更(文字の後ろで強制改行), 文字を小さく14px, 文字を中太 -->
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">探索先</label>
                        <!-- 横幅100% -->
                        <select name="location_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-300">
                            <option value="1">初心者の森</option>
                            <option value="2">迷いの洞窟</option>
                            <option value="3">静かな山道</option>
                        </select>
                    </div>

                    <div>
                        <lavel class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">戦闘方針</label>
                        <select name="stance_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-300">
                            <option value="1">慎重に進む</option>
                            <option value="2">普通</option>
                            <option value="3">静かな山道</option>
                        </select>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="button" class="px-4 py-2 bg-indigo-400 hover:bg-indigo-700 rounded font-bold text-sm shadow">
                        行動を予約
                    </button>
                </div>
            </form>
        </div>

        <!-- ゲームログ画面 -->
        <!-- <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">冒険ログ</h3>
            <div class="space-y-2">
        </div> -->
    </div>
</x-app-layout>
