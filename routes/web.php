<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\PetController as AdminPetController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Public\AdoptionRequestController;
use App\Http\Controllers\Public\DonationController as PublicDonationController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PetController as PublicPetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// --- PUBLIC ROUTES ---
Route::name('public.')->group(function () {
    Route::get('/', [PublicPetController::class, 'welcome'])->name('home');
    Route::get('/mascotas', [PublicPetController::class, 'index'])->name('pets.index');
    Route::get('/mascotas/{slug}', [PublicPetController::class, 'show'])->name('pets.show');

    // Guest Forms
    Route::post('/adopcion', [AdoptionRequestController::class, 'store'])->name('adoption-requests.store');
    Route::get('/donar', [PublicDonationController::class, 'create'])->name('donations.create');
    Route::post('/donar', [PublicDonationController::class, 'store'])->name('donations.store');

    // Static Pages
    Route::get('/acerca-de', [PageController::class, 'about'])->name('about');
    Route::get('/colabora', [PageController::class, 'support'])->name('support');
});


// --- ADMIN / PROTECTED ROUTES ---

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Resources
    Route::patch('pets/{pet}/status', [AdminPetController::class, 'updateStatus'])->name('pets.update-status');
    Route::resource('pets', AdminPetController::class);
    Route::resource('donations', AdminDonationController::class)->only(['index', 'update']);
    Route::resource('adoption-requests', \App\Http\Controllers\Admin\AdoptionRequestController::class)->only(['index', 'update']);
    Route::resource('payment-methods', \App\Http\Controllers\Admin\PaymentMethodController::class)->only(['store', 'update', 'destroy']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});


require __DIR__.'/auth.php';
