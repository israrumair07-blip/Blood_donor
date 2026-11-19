<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DonorContactRequestController;
use App\Http\Controllers\BloodExchangeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

// Homepage and Find Donor Navigation
Route::get('/', [DonorController::class, 'publicSearch'])->name('home');
Route::get('/find-donor', [DonorController::class, 'publicSearch'])->name('donors.public.search');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('dashboard')->with('success', 'Email verified. You can now access the admin dashboard.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Public Routes - Donors
Route::get('/become-a-donor', [DonorController::class, 'publicRegisterForm'])->name('donors.public.create');
Route::post('/become-a-donor', [DonorController::class, 'store'])->name('donors.public.store');
Route::get('/my-card/{token}', [DonorController::class, 'claim'])->name('donors.claim');
// Secure Route for Registered Donor to claim donation
Route::post('/donors/{donor}/notify-donation', [DonorController::class, 'notifyDonation'])->name('donors.public.notifyDonation');
Route::post('/donors/{donor}/toggle-alerts', [DonorController::class, 'toggleAlerts'])->name('donors.toggleAlerts');
Route::post('/donors/{donor}/update-email', [DonorController::class, 'updateEmail'])->name('donors.updateEmail');
Route::get('/donors/alerts/disable/{token}', [DonorController::class, 'disableAlertsDirect'])->name('donors.alerts.disable');

// Request to Contact Donor & User Request Status Tracker
Route::post('/donor-contacts', [DonorContactRequestController::class, 'store'])->name('donor-contacts.store');
Route::get('/donor-contacts/track/{token}', [DonorContactRequestController::class, 'track'])->name('donor-contacts.track');
Route::get('/my-requests', [DonorContactRequestController::class, 'myRequests'])->name('donor-contacts.my-requests');

// Blood Exchange System
Route::get('/blood-exchanges', [BloodExchangeController::class, 'index'])->name('blood-exchanges.index');
Route::get('/blood-exchanges/create', [BloodExchangeController::class, 'create'])->name('blood-exchanges.create');
Route::post('/blood-exchanges', [BloodExchangeController::class, 'store'])->name('blood-exchanges.store');
Route::get('/blood-exchanges/track/{token}', [BloodExchangeController::class, 'track'])->name('blood-exchanges.track');
Route::post('/blood-exchanges/{id}/cancel', [BloodExchangeController::class, 'cancel'])->name('blood-exchanges.cancel');

// Public Routes - Urgent Blood Requests
Route::get('/urgent-requests', [BloodRequestController::class, 'index'])->name('blood-requests.index');
Route::get('/request-blood', [BloodRequestController::class, 'create'])->name('blood-requests.create');
Route::post('/request-blood', [BloodRequestController::class, 'store'])->name('blood-requests.store');
Route::post('/urgent-requests/{id}/fulfill', [BloodRequestController::class, 'fulfill'])->name('blood-requests.fulfill');

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/test-email', function () {
        $target = config('services.admin.email') ?: config('mail.from.address');
        abort_unless($target, 503, 'No admin email recipient is configured.');

        Mail::raw('Testing Blood Donor System Mailer at ' . now()->toDateTimeString(), function ($message) use ($target) {
            $message->to($target)->subject('Test Email from Blood Donor System');
        });

        return '<div style="font-family:sans-serif;padding:30px;max-width:600px;margin:40px auto;border:2px solid #22c55e;border-radius:12px;background:#f0fdf4;">
            <h2 style="color:#15803d;margin-top:0;">Email Delivered Successfully!</h2>
            <p>A test email has been sent to <strong>' . e($target) . '</strong>.</p>
            <p>Check the configured admin inbox.</p>
        </div>';
    });

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::resource('donors', DonorController::class);
    Route::post('/donors/{donor}/toggle-status', [DonorController::class, 'toggleStatus'])->name('donors.toggleStatus');
    
    // Admin donation actions
    Route::post('/donors/{id}/confirm-donation', [DonorController::class, 'confirmDonation'])->name('donors.confirmDonation');
    Route::post('/donors/{id}/reject-donation', [DonorController::class, 'rejectDonation'])->name('donors.rejectDonation');
    Route::post('/donors/{id}/mark-donated', [DonorController::class, 'markDonated'])->name('donors.markDonated');
    Route::post('/donors/{donor}/claim-link', [DonorController::class, 'claimLink'])->name('donors.claimLink');
    Route::post('/donors', [DonorController::class, 'adminStore'])->name('donors.store');

    // Admin Urgent Request management
    Route::get('/admin/urgent-requests', [BloodRequestController::class, 'adminIndex'])->name('admin.urgent-requests.index');
    Route::delete('/admin/urgent-requests/{bloodRequest}', [BloodRequestController::class, 'destroy'])->name('blood-requests.destroy');

    // Admin Donor Contact Requests Management
    Route::get('/admin/contact-requests', [DonorContactRequestController::class, 'adminIndex'])->name('admin.contact-requests.index');
    Route::post('/admin/contact-requests/{contactRequest}/approve', [DonorContactRequestController::class, 'adminApprove'])->name('admin.contact-requests.approve');
    Route::post('/admin/contact-requests/{contactRequest}/reject', [DonorContactRequestController::class, 'adminReject'])->name('admin.contact-requests.reject');
    Route::delete('/admin/contact-requests/{contactRequest}', [DonorContactRequestController::class, 'adminDestroy'])->name('admin.contact-requests.destroy');

    // Admin Blood Exchange Management
    Route::get('/admin/blood-exchanges', [BloodExchangeController::class, 'adminIndex'])->name('admin.blood-exchanges.index');
    Route::post('/admin/blood-exchanges/{bloodExchange}/approve', [BloodExchangeController::class, 'adminApprove'])->name('admin.blood-exchanges.approve');
    Route::post('/admin/blood-exchanges/{bloodExchange}/reject', [BloodExchangeController::class, 'adminReject'])->name('admin.blood-exchanges.reject');
    Route::post('/admin/blood-exchanges/{bloodExchange}/complete', [BloodExchangeController::class, 'adminComplete'])->name('admin.blood-exchanges.complete');
    Route::delete('/admin/blood-exchanges/{bloodExchange}', [BloodExchangeController::class, 'adminDestroy'])->name('admin.blood-exchanges.destroy');
});
