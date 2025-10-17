<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsActiveUser;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\UserDashboardController;

// Public ruta
Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth', 'verified', IsActiveUser::class])->group(function () {
     Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Profile rute
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified', IsAdmin::class, IsActiveUser::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});


require __DIR__.'/auth.php';
