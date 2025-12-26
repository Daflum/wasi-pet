<?php

use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// --- PUBLIC ROUTES ---
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/mascotas', function () {
    // Placeholder. In future: [PetController::class, 'index']
    return Inertia::render('Welcome');
})->name('pets.index');

Route::get('/donar', [DonationController::class, 'index'])->name('donations.index');
Route::post('/adoption-requests', [AdoptionRequestController::class, 'store'])->name('adoption-requests.store');
Route::post('/donar', [DonationController::class, 'store'])->name('donations.store');

// --- ADMIN / PROTECTED ROUTES ---

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Features (Protected by Admin Role)
    Route::middleware('admin')->group(function () {
        Route::get('/donations', [DonationController::class, 'indexAdmin'])->name('donations.index');
    });
});

require __DIR__.'/auth.php';
