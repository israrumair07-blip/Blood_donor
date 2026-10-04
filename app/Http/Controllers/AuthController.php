<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Donor;
use App\Models\User;
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

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
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
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Dashboard View with Donor Metrics
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

    return view('dashboard', compact('totalDonors', 'availableDonors', 'recentDonors', 'pendingDonors'));
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