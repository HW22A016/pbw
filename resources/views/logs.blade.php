<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ログ履歴画面') }}
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
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">ログ情報</h3>

                @if($logs)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">行動回数</th>
                                    <th class="px-4 py-2 text-left">メッセージ</th>
                                    <th class="px-4 py-2 text-left">獲得経験値</th>
                                    <th class="px-4 py-2 text-left">獲得ゴールド</th>
                                    <th class="px-4 py-2 text-left">受けたダメージ</th>
                                    <th class="px-4 py-2 text-left">日時</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($logs as $log)
                                    <tr>
                                        <td class="px-4 py-3">#{{ $log->turn_number }}</td>
                                        <td class="px-4 py-3">{{ $log->message }}</td>
                                        <td class="px-4 py-3">{{ $log->gained_exp }}</td>
                                        <td class="px-4 py-3">{{ $log->gained_gold }}</td>
                                        <td class="px-4 py-3">{{ $log->hp_change }}</td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($log->created_at)->format('Y年m月d日') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400">まだ行動をしていません。</p>
                @endif
            </div>
        </div>

</x-app-layout>