<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBloodExchangeRequest;
use App\Models\BloodExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BloodExchangeController extends Controller
{
    /**
     * Public: Browse active approved blood exchange offers
     */
    public function index(Request $request)
    {
        $query = BloodExchange::whereIn('status', ['approved', 'completed']);

        if ($request->filled('offered_blood_group')) {
            $query->where('offered_blood_group', $request->offered_blood_group);
        }

        if ($request->filled('needed_blood_group')) {
            $query->where('needed_blood_group', $request->needed_blood_group);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Show open/approved exchanges first
        $query->orderByRaw("CASE WHEN status = 'approved' THEN 1 ELSE 2 END");

        $exchanges = $query->latest()->paginate(9)->withQueryString();

        return view('blood_exchanges.index', compact('exchanges'));
    }

    /**
     * Public: Create blood exchange offer form
     */
    public function create()
    {
        return view('blood_exchanges.create');
    }

    /**
     * Public: Store blood exchange request
     */
    public function store(StoreBloodExchangeRequest $request)
    {
        $validated = $request->validated();
        $validated['status']       = 'pending';
        $validated['manage_token'] = Str::random(60);

        $exchange = BloodExchange::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            app()->terminating(function () use ($exchange) {
                $this->alertAdmin($exchange);
            });

            return response()->json([
                'success' => true,
                'message' => 'Your blood exchange request has been submitted for admin verification.',
                'tracking_token' => $exchange->manage_token,
                'tracking_url' => route('blood-exchanges.track', $exchange->manage_token),
            ]);
        }

        $this->alertAdmin($exchange);

        return redirect()->route('blood-exchanges.track', $exchange->manage_token)
            ->with('success', 'Your blood exchange request has been submitted for admin verification. You can track its live status below.');
    }

    /**
     * Public: Track the status of an exchange request
     */
    public function track($token)
    {
        $exchange = BloodExchange::where('manage_token', $token)->firstOrFail();

        return view('blood_exchanges.track', compact('exchange'));
    }

    /**
     * Public or Admin: Mark exchange completed or cancelled
     */
    public function cancel(Request $request, $id)
    {
        $exchange = BloodExchange::findOrFail($id);

        $token = $request->input('manage_token');
        if (!auth()->check() && (!hash_equals((string) $exchange->manage_token, (string) $token))) {
            abort(403, 'Unauthorized');
        }

        $exchange->update(['status' => 'cancelled']);

        return back()->with('info', 'Blood exchange request has been cancelled.');
    }

    /**
     * Admin: List all exchange requests
     */
    public function adminIndex(Request $request)
    {
        $query = BloodExchange::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END");
        }

        if ($request->filled('blood_group')) {
            $query->where(function ($q) use ($request) {
                $q->where('offered_blood_group', $request->blood_group)
                  ->orWhere('needed_blood_group', $request->blood_group);
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $exchanges = $query->latest()->paginate(15)->withQueryString();

        return view('admin.blood_exchanges.index', compact('exchanges'));
    }

    /**
     * Admin: Approve exchange offer (makes it live on exchange board)
     */
    public function adminApprove(Request $request, BloodExchange $bloodExchange)
    {
        $adminNotes = $request->input('admin_notes', 'Verified and approved by admin. Exchange offer is now public.');

        $bloodExchange->update([
            'status'      => 'approved',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Blood exchange #{$bloodExchange->id} approved and made live.",
                'type' => 'success',
            ]);
        }

        return back()->with('success', "Blood exchange #{$bloodExchange->id} approved and made live.");
    }

    /**
     * Admin: Reject exchange offer
     */
    public function adminReject(Request $request, BloodExchange $bloodExchange)
    {
        $request->validate(['admin_notes' => 'required|string|max:500']);

        $bloodExchange->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Blood exchange #{$bloodExchange->id} rejected.",
                'type' => 'info',
            ]);
        }

        return back()->with('info', "Blood exchange #{$bloodExchange->id} rejected.");
    }

    /**
     * Admin: Mark exchange completed
     */
    public function adminComplete(BloodExchange $bloodExchange)
    {
        $bloodExchange->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Blood exchange #{$bloodExchange->id} marked as successfully completed!",
                'type' => 'success',
            ]);
        }

        return back()->with('success', "Blood exchange #{$bloodExchange->id} marked as successfully completed!");
    }

    /**
     * Admin: Delete exchange record
     */
    public function adminDestroy(BloodExchange $bloodExchange)
    {
        $bloodExchange->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Exchange record deleted.',
                'type' => 'success',
            ]);
        }

        return back()->with('success', 'Exchange record deleted.');
    }

    /**
     * Alert admin when a new blood exchange request is submitted
     */
    private function alertAdmin(BloodExchange $ex): void
    {
        $adminEmail = config('services.admin.email') ?: env('ADMIN_EMAIL') ?: config('mail.from.address');
        if (!$adminEmail) return;

        try {
            $text = "Assalam-o-Alaikum Admin,\n\n"
                  . "A new Blood Exchange offer has been posted on Blood Donor Network:\n\n"
                  . "Offered Blood Group: {$ex->offered_blood_group}\n"
                  . "Needed Blood Group: {$ex->needed_blood_group}\n"
                  . "Units: {$ex->units}\n"
                  . "Patient: {$ex->patient_name}\n"
                  . "Hospital: {$ex->hospital}, {$ex->city}\n"
                  . "Contact: {$ex->contact_name} ({$ex->contact_phone})\n"
                  . "Urgency: {$ex->urgency_level}\n"
                  . ($ex->notes ? "Notes: \"{$ex->notes}\"\n\n" : "\n")
                  . "Review and approve here:\n"
                  . route('admin.blood-exchanges.index') . "\n\n"
                  . "Regards,\nBlood Donor Network";

            \Illuminate\Support\Facades\Mail::raw($text, function ($m) use ($adminEmail, $ex) {
                $m->to($adminEmail)->subject("🔄 New Blood Exchange Offer: {$ex->offered_blood_group} for {$ex->needed_blood_group}");
            });
            \Illuminate\Support\Facades\Log::info("Admin alert sent for Blood Exchange #{$ex->id}");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Could not email admin for Blood Exchange #{$ex->id}: " . $e->getMessage());
        }
    }
}

