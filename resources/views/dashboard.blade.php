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
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->attack }}</div>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded text-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">所持金</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $character->gold }}G</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 行動予約フォーム -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">次の定期更新の行動予約</h3>

            <!-- 残り時間 -->
            <!-- font-mono等幅フォント -->
            <div class="font-extrabold text-red-600 dark:text-red-400 font-mono tracking-tight">
                <div class="text-xs text-gray-500 dark:text-gray-400">残り時間</div>
                <span id="countdown">{{ $nextUpdateTime }}</span>
            </div>

            <!-- 現在の予約状況 -->
            @if($currentReserve)
                <!-- マージン下に24pxの余白, パディング16px -->
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-900 text-green-800 dark:text-green-200 rounded">
                    現在の予約: <strong>探索先[{{ $currentReserve?->location_id->label() }}]</strong> / <strong>戦闘方針[{{ $currentReserve?->stance_id->label() }}]</strong>
                </div>
            @else
                <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950 border border-amber-200 dark:border-amber-900 text-amber-800 dark:text-amber-200 rounded">
                    現在、行動が予約されていません
                </div>
            @endif

            <!-- 送信フォーム -->
             <!-- 子要素同士の上下の間隔をまとめて開ける16px -->
            <form action="{{ route('reserve.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <!-- ブロック要素に変更(文字の後ろで強制改行), 文字を小さく14px, 文字を中太 -->
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">探索先</label>
                        <!-- 横幅100% -->
                        <select name="location_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-300">
                            @foreach(\App\Enums\Location::cases() as $location)
                                <option value="{{ $location->value }}">
                                    {{ $location->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <lavel class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">戦闘方針</label>
                        <select name="stance_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-300">
                            @foreach(\App\Enums\stance::cases() as $stance)
                                <option value="{{ $stance->value }}">
                                    {{ $stance->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="px-4 py-2 bg-indigo-400 hover:bg-indigo-700 rounded font-bold text-sm shadow">
                        行動を予約
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function()
        {
            const targetTimeStr = "{{ $nextUpdateTime }}";
            const targetTime = new Date(targetTimeStr).getTime();
            const timerElement = document.getElementById('countdown');

            function updateTimer()
            {
                const now = new Date().getTime();
                // 期限時刻までの残り時間(秒)
                let distance = Math.floor((targetTime - now) / 1000);

                // 時間の計算
                const hours = Math.floor(distance / (60 * 60));
                distance -= hours * (60 * 60);
                const minutes = Math.floor(distance / 60);
                distance -= minutes * 60;
                const seconds = Math.floor(distance);

                // 1桁の時2桁に揃える
                const h = String(hours).padStart(2, '0');
                const m = String(minutes).padStart(2, '0');
                const s = String(seconds).padStart(2, '0');

                timerElement.innerHTML = `${h}:${m}:${s}`;
            }
            updateTimer();
            // 1秒ごとに実行
            setInterval(updateTimer, 1000);
        });
    </script>
</x-app-layout>
