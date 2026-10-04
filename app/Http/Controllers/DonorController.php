<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonorRequest;
use App\Http\Requests\UpdateDonorRequest;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonorController extends Controller
{
    /* ---------------------------------------------------------------
     |  PUBLIC
     * ------------------------------------------------------------- */

    public function publicRegisterForm()
    {
        return view('donors.public_register');
    }

    // Public registration: creates the donor and returns a secret token for this browser
    public function store(StoreDonorRequest $request)
    {
        $validated = $request->validated();

        $validated['is_available'] = $request->boolean('is_available');
        $validated['donor_token']  = Str::random(60);

        if ($validated['gender'] !== 'Female') {
            $validated['guardian_name'] = $validated['guardian_relation'] = $validated['guardian_phone'] = null;
        }

        $donor = Donor::create($validated);

        return response()->json([
            'success'     => true,
            'message'     => 'You have been registered successfully!',
            'donor_id'    => $donor->id,
            'donor_token' => $validated['donor_token'],
        ], 201);
    }

    public function publicSearch(Request $request)
    {
        $query = Donor::query();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $donors = $query->latest()->paginate(9)->withQueryString();

        return view('donors.public_search', compact('donors'));
    }

    // Donor tells the admin "I donated today" (verified by the secret token)
    public function notifyDonation(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $token = $request->header('X-Donor-Token') ?? $request->input('donor_token');

        if (!$donor->donor_token || !$token || !hash_equals($donor->donor_token, $token)) {
            return response()->json(['message' => 'Unauthorized action!'], 403);
        }

        if ($donor->donation_pending) {
            return response()->json(['message' => 'Verification is already pending.'], 422);
        }

        if (!$donor->is_eligible) {
            return response()->json(['message' => 'You are still in the cooldown period.'], 422);
        }

        $donor->update(['donation_pending' => true]);
        $this->alertAdmin($donor);

        return response()->json([
            'message' => 'Notification sent to admin. Your status will update after verification.',
        ]);
    }

    /* ---------------------------------------------------------------
     |  ADMIN
     * ------------------------------------------------------------- */

    public function index(Request $request)
    {
        $query = Donor::query();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $donors = $query->latest()->paginate(10)->withQueryString();

        return view('donors.index', compact('donors'));
    }

    // Admin "Add Donor" modal (same fields as public form, but no token)
    public function adminStore(StoreDonorRequest $request)
    {
        $validated = $request->validated();

        $validated['is_available'] = $request->boolean('is_available');
        $validated['donor_token']  = Str::random(60);   // lets the donor claim this card via a personal link

        if ($validated['gender'] !== 'Female') {
            $validated['guardian_name'] = $validated['guardian_relation'] = $validated['guardian_phone'] = null;
        }

        $donor = Donor::create($validated);

        return response()->json([
            'message' => 'Donor added successfully!',
            'donor'   => $this->donorPayload($donor),
        ], 201);
    }

    // The edit modal sends the checkbox as "available"
    public function update(UpdateDonorRequest $request, Donor $donor)
    {
        $validated = $request->validated();
        $validated['is_available'] = $request->boolean('available');

        if ($validated['gender'] !== 'Female') {
            $validated['guardian_name'] = $validated['guardian_relation'] = $validated['guardian_phone'] = null;
        }

        $donor->update($validated);

        return response()->json([
            'message' => 'Donor updated successfully!',
            'donor'   => $this->donorPayload($donor),
        ]);
    }

    public function destroy(Donor $donor)
    {
        $donor->delete();

        return response()->json(['message' => 'Donor deleted.']);
    }

    public function toggleStatus(Donor $donor)
    {
        $donor->update(['is_available' => !$donor->is_available]);

        return response()->json([
            'success'      => true,
            'is_available' => $donor->is_available,
            'message'      => 'Status updated.',
        ]);
    }

    // Admin confirms a donor's request and starts the 90-day cooldown
    public function confirmDonation($id)
    {
        $donor = Donor::findOrFail($id);

        $donor->update([
            'last_donated_at'  => now()->toDateString(),
            'donation_pending' => false,
        ]);

        return back()->with('success', "{$donor->name}'s donation has been confirmed. Cooldown is now active.");
    }

    public function rejectDonation($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->update(['donation_pending' => false]);

        return back()->with('info', 'The donation request has been cancelled.');
    }

    // Admin marks a donation directly (no donor request needed)
    public function markDonated($id)
    {
        $donor = Donor::findOrFail($id);

        $donor->update([
            'last_donated_at'  => now()->toDateString(),
            'donation_pending' => false,
        ]);

        return back()->with('success', "{$donor->name} has been marked as donated. Cooldown is now active.");
    }

    // Public: a donor opens the personal link sent by the admin (stores the token in this browser)
    public function claim(string $token)
    {
        $donor = Donor::where('donor_token', $token)->firstOrFail();

        return view('donors.claim', ['donor' => $donor, 'token' => $token]);
    }

    // Admin: returns the donor's personal link (creates the token first if the donor has none)
    public function claimLink(Donor $donor)
    {
        if (!$donor->donor_token) {
            $donor->update(['donor_token' => Str::random(60)]);
        }

        return response()->json(['url' => route('donors.claim', $donor->donor_token)]);
    }

    // Emails the admin when a donor claims a donation
    private function alertAdmin(Donor $donor): void
    {
        $text = "New donation request\n\n"
              . "Donor: {$donor->name}\n"
              . "Blood group: {$donor->blood_group}\n"
              . "City: {$donor->city}\n"
              . "Phone: {$donor->phone}\n\n"
              . "Review it here: " . route('dashboard');

        try {
            Mail::raw($text, function ($m) use ($donor) {
                $m->to(config('services.admin.email'))
                  ->subject('Donation request: ' . $donor->name);
            });
        } catch (\Throwable $e) {
            // If email fails, the donor's request still goes through
            Log::warning('Admin email failed: ' . $e->getMessage());
        }
    }

    // The admin JS reads "available", so map it from is_available
    private function donorPayload(Donor $donor): array
    {
        return $donor->toArray() + ['available' => $donor->is_available];
    }
}