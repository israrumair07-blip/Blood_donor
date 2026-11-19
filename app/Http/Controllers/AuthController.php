<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Donor;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\DonorContactRequest;
use App\Models\BloodExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registration Views & Logic
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $user->sendEmailVerificationNotification();

        return redirect()
            ->route('verification.notice')
            ->with('success', 'Account created. Verify your email address to access the admin dashboard.');
    }

    // Login Views & Logic
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = $request->user();

            if (!$user->isAdminEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'This email address is not authorized for admin access.',
                ])->onlyInput('email');
            }

            if (!$user->hasVerifiedEmail()) {
                return redirect()
                    ->route('verification.notice')
                    ->with('status', 'Please verify your email address before accessing the admin dashboard.');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Dashboard View with Donor & Request Metrics
    public function dashboard()
    {
        $totalDonors = Donor::count();

        // Available = switched on AND not in the 90-day cooldown
        $availableDonors = Donor::where('is_available', true)
            ->where(function ($q) {
                $q->whereNull('last_donated_at')
                  ->orWhere('last_donated_at', '<=', now()->subDays(90)->toDateString());
            })->count();

        $recentDonors  = Donor::latest()->take(5)->get();
        $pendingDonors = Donor::where('donation_pending', true)->latest('updated_at')->get();

        // Urgent Blood Requests
        $openRequestsCount = BloodRequest::open()->count();
        $openRequests      = BloodRequest::open()->latest()->take(5)->get();

        // Donor Contact Requests
        $pendingContactRequestsCount = DonorContactRequest::pending()->count();
        $pendingContactRequests      = DonorContactRequest::with('donor')->pending()->latest()->take(5)->get();

        // Blood Exchanges
        $pendingExchangesCount = BloodExchange::pending()->count();
        $pendingExchanges      = BloodExchange::pending()->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalDonors',
            'availableDonors',
            'recentDonors',
            'pendingDonors',
            'openRequestsCount',
            'openRequests',
            'pendingContactRequestsCount',
            'pendingContactRequests',
            'pendingExchangesCount',
            'pendingExchanges'
        ));
    }

    // Logout Logic
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
