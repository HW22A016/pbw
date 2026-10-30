<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// urlに/dashboardと打ち込んでDashboardControllerのindex関数を実行する
Route::get('/dashboard', [DashboardController::class, 'index'])
// ログインしているかつ登録されたメールアドレスが本人のものか認証
->middleware(['auth', 'verified'])
->name('dashboard');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/reserve', [ReserveController::class, 'store'])->name('reserve.store');

Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
