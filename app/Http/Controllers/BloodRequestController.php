<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloodRequestRequest;
use App\Mail\UrgentBloodAlertMail;
use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BloodRequestController extends Controller
{
    /**
     * Public Feed: Active Urgent Blood Requests
     */
    public function index(Request $request)
    {
        $query = BloodRequest::query();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: show open requests first, then recently fulfilled
            $query->orderByRaw("CASE WHEN status = 'open' THEN 1 ELSE 2 END");
        }

        $requests = $query->latest()->paginate(9)->withQueryString();

        return view('blood_requests.index', compact('requests'));
    }

    /**
     * Admin: Manage all urgent blood requests, including fulfilled requests.
     */
    public function adminIndex(Request $request)
    {
        $query = BloodRequest::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.urgent_requests.index', compact('requests'));
    }

    /**
     * Public: Show create request form
     */
    public function create()
    {
        return view('blood_requests.create');
    }

    /**
     * Public: Store urgent blood request, make it live immediately, and notify matching donors
     */
    public function store(StoreBloodRequestRequest $request)
    {
        $validated = $request->validated();
        $validated['status']       = 'open';
        $validated['manage_token'] = Str::random(60);

        $bloodRequest = BloodRequest::create($validated);

        $myTokens = session()->get('my_blood_request_tokens', []);
        $myTokens[$bloodRequest->id] = $validated['manage_token'];
        session()->put('my_blood_request_tokens', $myTokens);

        if ($request->ajax() || $request->wantsJson()) {
            app()->terminating(function () use ($bloodRequest) {
                $this->alertMatchingDonors($bloodRequest);
            });

            return response()->json([
                'success' => true,
                'message' => 'Your urgent blood request is live. Donor alerts are being sent now.',
                'request_id' => $bloodRequest->id,
                'manage_token' => $validated['manage_token'],
            ]);
        }

        $alertSummary = $this->alertMatchingDonors($bloodRequest);
        $matchingDonorCount = $alertSummary['matching_count'];
        $notifiedCount = $alertSummary['notified_count'];

        if ($notifiedCount > 0) {
            $donorMsg = "{$notifiedCount} registered " . Str::plural('donor', $notifiedCount) . " in {$bloodRequest->city} have been alerted via email!";
        } elseif ($matchingDonorCount > 0) {
            $donorMsg = "{$matchingDonorCount} matching " . Str::plural('donor', $matchingDonorCount) . " found in {$bloodRequest->city}, but email delivery failed. Please check mail settings in .env.";
        } else {
            $donorMsg = "Request is live across the network! (Note: No eligible donors currently registered for {$bloodRequest->blood_group} in {$bloodRequest->city}).";
        }

        $successMessage = "Your urgent blood request is LIVE. {$donorMsg}";

        return redirect()
            ->route('blood-requests.index')
            ->with('success', $successMessage)
            ->with('new_request_id', $bloodRequest->id)
            ->with('new_manage_token', $validated['manage_token']);
    }

    private function alertMatchingDonors(BloodRequest $bloodRequest): array
    {
        // Find eligible matching donors in the same city & blood group with alert enabled
        $matchingDonors = Donor::availableForUrgentAlert($bloodRequest->blood_group, $bloodRequest->city)->get();
        $notifiedCount  = 0;

        Log::info("Urgent request created #{$bloodRequest->id}: Group '{$bloodRequest->blood_group}' in City '{$bloodRequest->city}'");
        Log::info("Matching donors count: " . $matchingDonors->count());

        foreach ($matchingDonors as $donor) {
            $sent = false;
            for ($attempt = 1; $attempt <= 3; $attempt++) {
                try {
                    Log::info("Sending urgent alert email to donor #{$donor->id} ({$donor->email}) - Attempt {$attempt}");
                    Mail::to($donor->email)->send(new UrgentBloodAlertMail($bloodRequest, $donor));
                    $notifiedCount++;
                    $sent = true;
                    Log::info("Urgent alert email successfully delivered to {$donor->email}");
                    break;
                } catch (\Throwable $e) {
                    Log::warning("Attempt {$attempt} failed for {$donor->email}: " . $e->getMessage());
                    if ($attempt < 3) {
                        sleep(1); // Wait 1 second before retrying in case of temporary network/DNS jitter
                    }
                }
            }
            if (!$sent) {
                Log::error("Failed to send urgent email to {$donor->email} after 3 attempts.");
            }
        }

        return [
            'matching_count' => $matchingDonors->count(),
            'notified_count' => $notifiedCount,
        ];
    }

    /**
     * Mark a request as fulfilled (Can be done by requester with token or logged in admin)
     */
    public function fulfill(Request $request, $id)
    {
        $bloodRequest = BloodRequest::findOrFail($id);

        $token = $request->input('manage_token') ?? session("my_blood_request_tokens.{$bloodRequest->id}");

        $isAuthorized = auth()->check() || ($token && hash_equals((string) $bloodRequest->manage_token, (string) $token));

        if (!$isAuthorized) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }
            return back()->with('error', 'Unauthorized to mark this request.');
        }

        $bloodRequest->update([
            'status'       => 'fulfilled',
            'fulfilled_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Request has been marked as fulfilled. Thank you to all donors!',
            ]);
        }

        return back()->with('success', 'Blood request marked as fulfilled! Thank you to everyone who stepped forward.');
    }

    /**
     * Admin: Delete blood request
     */
    public function destroy(Request $request, BloodRequest $bloodRequest)
    {
        $bloodRequest->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blood request deleted successfully.',
            ]);
        }

        return back()->with('success', 'Blood request deleted successfully.');
    }
}

