<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('キャラクター一覧') }}
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
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">キャラクター情報</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">名前</th>
                                <th class="px-4 py-2 text-left">level</th>
                                <th class="px-4 py-2 text-left">HP</th>
                                <th class="px-4 py-2 text-left">attack</th>
                                <th class="px-4 py-2 text-left">gold</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($characters as $character)
                                <tr>
                                    <td class="px-4 py-3">{{ $character->user->name }}</td>
                                    <td class="px-4 py-3">{{ $character->level }}</td>
                                    <td class="px-4 py-3">{{ $character->hp }}</td>
                                    <td class="px-4 py-3">{{ $character->attack }}</td>
                                    <td class="px-4 py-3">{{ $character->gold }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>