<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use function Laravel\Prompts\error;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile-legacy', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile-legacy', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-legacy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/app.php';
