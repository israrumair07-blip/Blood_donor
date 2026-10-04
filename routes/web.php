<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;

Route::get('/', function () {
    return redirect()->route('donors.public.search');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Public Routes
Route::get('/find-donor', [DonorController::class, 'publicSearch'])->name('donors.public.search');
Route::get('/become-a-donor', [DonorController::class, 'publicRegisterForm'])->name('donors.public.create');
Route::post('/become-a-donor', [DonorController::class, 'store'])->name('donors.public.store');
Route::get('/my-card/{token}', [DonorController::class, 'claim'])->name('donors.claim');
// Secure Route for Registered Donor to claim donation
Route::post('/donors/{donor}/notify-donation', [DonorController::class, 'notifyDonation'])->name('donors.public.notifyDonation');

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('donors', DonorController::class);
    Route::post('/donors/{donor}/toggle-status', [DonorController::class, 'toggleStatus'])->name('donors.toggleStatus');
    
    // Admin donation actions
    Route::post('/donors/{id}/confirm-donation', [DonorController::class, 'confirmDonation'])->name('donors.confirmDonation');
    Route::post('/donors/{id}/reject-donation', [DonorController::class, 'rejectDonation'])->name('donors.rejectDonation');
    Route::post('/donors/{id}/mark-donated', [DonorController::class, 'markDonated'])->name('donors.markDonated');
   Route::post('/donors/{donor}/claim-link', [DonorController::class, 'claimLink'])->name('donors.claimLink');
    Route::post('/donors', [DonorController::class, 'adminStore'])->name('donors.store');
});
