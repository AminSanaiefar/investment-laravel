<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUser;

Route::get('/', function () {
    return view('welcome');
});

// Only Authenticated Verified And User Role Can Access This->
Route::middleware(['auth', 'verified', IsUser::class])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Only Authenticated Verified And Admin Role Can Access This->
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
