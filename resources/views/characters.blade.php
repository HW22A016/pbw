<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('キャラクターランキング') }}
        </h2>
    </x-slot>

    <!-- ステータス画面 -->
    <div class="py-12">
        <div class="mb-4 flex justify-center bg-white rounded-2xl p-6 max-w-max mx-auto">
            <form action="{{ route('characters.index') }}" method="GET" class="flex items-center space-x-2">
                <label for="sort" class="text-sm font-medium text-gray-700 dark:text-gray-300">並び替え:</label>
                <!-- focus:border-indigo-300:クリックした時の枠線の色を青紫色にする focus:ring:クリックした時にリングを発生 focus:ring-indigo-200:リングの色を薄い青紫色にする focus:ring-opacity-50:リングの透明度50% -->
                <select name="sort" id="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="level" {{ request('sort') == 'level' ? 'selected' : '' }}>レベル</option>
                    <option value="hp" {{ request('sort') == 'hp' ? 'selected' : '' }}>HP</option>
                    <option value="attack" {{ request('sort') == 'attack' ? 'selected' : '' }}>攻撃力</option>
                    <option value="gold" {{ request('sort') == 'gold' ? 'selected' : '' }}>所持金</option>
                </select>
            </form>
        </div>
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
                                <th class="px-4 py-2 text-left">順位</th>
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
                                    <td class="px-4 py-3">{{ $loop->iteration }}位</td>
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