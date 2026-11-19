<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonorContactRequest;
use App\Models\Donor;
use App\Models\DonorContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DonorContactRequestController extends Controller
{
    /**
     * Public: Submit a request to contact a donor (Admin-mediated, donor private info hidden)
     */
    public function store(StoreDonorContactRequest $request)
    {
        $validated = $request->validated();
        $validated['status']         = 'pending';
        $validated['tracking_token'] = Str::random(60);

        $contactRequest = DonorContactRequest::create($validated);

        // Store request ID in user session so they can easily track their requests
        $request->session()->push('user_contact_requests', $contactRequest->id);

        if ($request->ajax() || $request->wantsJson()) {
            app()->terminating(function () use ($contactRequest) {
                $this->notifyAdmin($contactRequest);
            });

            return response()->json([
                'success' => true,
                'message' => 'Your request to contact this donor has been submitted to the admin. You can track its status below.',
                'tracking_token' => $contactRequest->tracking_token,
            ]);
        }

        $this->notifyAdmin($contactRequest);

        return redirect()->route('donor-contacts.my-requests')
            ->with('success', 'Your request to contact this donor has been submitted to the admin. You can track its status below.');
    }

    /**
     * Public: "My Requests" page where users view all their submitted blood requests and statuses
     */
    public function myRequests(Request $request)
    {
        $phone = trim($request->input('phone', ''));
        $sessionIds = (array) $request->session()->get('user_contact_requests', []);

        $query = DonorContactRequest::with('donor');

        if (!empty($phone)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            $query->where(function ($q) use ($phone, $cleanPhone) {
                $q->where('requester_phone', 'like', '%' . $phone . '%')
                  ->orWhereRaw("REPLACE(REPLACE(REPLACE(requester_phone, ' ', ''), '-', ''), '+', '') LIKE ?", ['%' . $cleanPhone . '%']);
            });
            $requests = $query->latest()->paginate(10)->withQueryString();
        } elseif (!empty($sessionIds)) {
            $requests = $query->whereIn('id', $sessionIds)->latest()->paginate(10);
        } else {
            $requests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return view('donors.my_requests', compact('requests', 'phone'));
    }

    /**
     * Public: Track the status of a contact request
     */
    public function track($token)
    {
        $contactRequest = DonorContactRequest::with('donor')->where('tracking_token', $token)->firstOrFail();

        return view('donors.contact_request_track', compact('contactRequest'));
    }

    /**
     * Admin: List all donor contact requests
     */
    public function adminIndex(Request $request)
    {
        $query = DonorContactRequest::with('donor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END");
        }

        if ($request->filled('blood_group')) {
            $query->whereHas('donor', function ($q) use ($request) {
                $q->where('blood_group', $request->blood_group);
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('requester_name', 'like', "%{$s}%")
                  ->orWhere('requester_phone', 'like', "%{$s}%")
                  ->orWhere('patient_name', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%");
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        $allCount      = DonorContactRequest::count();
        $pendingCount  = DonorContactRequest::where('status', 'pending')->count();
        $approvedCount = DonorContactRequest::where('status', 'approved')->count();
        $rejectedCount = DonorContactRequest::where('status', 'rejected')->count();

        return view('admin.contact_requests.index', compact('requests', 'allCount', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Admin: Approve a contact request (Mediates contact without exposing donor publicly)
     */
    public function adminApprove(Request $request, DonorContactRequest $contactRequest)
    {
        $adminNotes = $request->input('admin_notes', 'Request approved by administrator. The donor has been notified with your patient details.');

        $contactRequest->update([
            'status'      => 'approved',
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);

        // Mediated communication: notify donor with requester details
        $this->notifyDonorOfApproval($contactRequest);

        // Notify requester that admin approved
        $this->notifyRequesterOfApproval($contactRequest);

        if ($request->ajax() || $request->wantsJson()) {
            $pendingCount = DonorContactRequest::where('status', 'pending')->count();
            return response()->json([
                'success'       => true,
                'message'       => "Contact request #{$contactRequest->id} approved. Donor has been notified.",
                'status'        => 'approved',
                'id'            => $contactRequest->id,
                'admin_notes'   => $contactRequest->admin_notes,
                'pending_count' => $pendingCount,
                'type'          => 'success',
            ]);
        }

        return back()->with('success', "Contact request #{$contactRequest->id} approved. Mediated communication initiated.");
    }

    /**
     * Admin: Reject a contact request
     */
    public function adminReject(Request $request, DonorContactRequest $contactRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $contactRequest->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $pendingCount = DonorContactRequest::where('status', 'pending')->count();
            return response()->json([
                'success'       => true,
                'message'       => "Contact request #{$contactRequest->id} rejected.",
                'status'        => 'rejected',
                'id'            => $contactRequest->id,
                'pending_count' => $pendingCount,
                'type'          => 'info',
            ]);
        }

        return back()->with('info', "Contact request #{$contactRequest->id} has been marked as rejected.");
    }

    /**
     * Admin: Delete a contact request regardless of its review status
     */
    public function adminDestroy(Request $request, DonorContactRequest $contactRequest)
    {
        $requestId = $contactRequest->id;
        $contactRequest->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => "Contact request #{$requestId} deleted.",
                'pending_count' => DonorContactRequest::where('status', 'pending')->count(),
                'all_count'     => DonorContactRequest::count(),
                'approved_count' => DonorContactRequest::where('status', 'approved')->count(),
                'rejected_count' => DonorContactRequest::where('status', 'rejected')->count(),
                'type'          => 'info',
            ]);
        }

        return back()->with('success', "Contact request #{$requestId} deleted.");
    }

    /**
     * Helper: Notify Admin when a contact request arrives
     */
    private function notifyAdmin(DonorContactRequest $req): void
    {
        $adminEmail = config('services.admin.email') ?: env('ADMIN_EMAIL') ?: config('mail.from.address');
        if (!$adminEmail) {
            Log::warning('No admin email configured for contact request notification.');
            return;
        }

        try {
            $req->loadMissing('donor');
            $donorName  = $req->donor ? $req->donor->name : 'Registered Donor';
            $donorGroup = $req->donor ? $req->donor->blood_group : '';

            $text = "Assalam-o-Alaikum Admin,\n\n"
                  . "A new request to contact a blood donor has been submitted on Blood Donor Network:\n\n"
                  . "Target Donor: {$donorName} ({$donorGroup})\n"
                  . "Requester: {$req->requester_name} (Phone: {$req->requester_phone})\n"
                  . "Email: " . ($req->requester_email ?: 'Not provided') . "\n"
                  . "Patient Name: {$req->patient_name}\n"
                  . "Hospital: {$req->hospital}, {$req->city}\n"
                  . "Urgency: {$req->urgency_level}\n"
                  . "Message / Condition: \"{$req->message}\"\n\n"
                  . "You can review, approve, or reject this request in your admin dashboard:\n"
                  . route('admin.contact-requests.index') . "\n\n"
                  . "Regards,\nBlood Donor Network System";

            Mail::raw($text, function ($m) use ($adminEmail, $donorName, $donorGroup) {
                $m->to($adminEmail)->subject("🔔 New Contact Request: {$donorName} ({$donorGroup})");
            });

            Log::info("Admin contact request email successfully dispatched to {$adminEmail} for donor {$donorName}");
        } catch (\Throwable $e) {
            Log::warning('Primary admin email dispatch attempt failed: ' . $e->getMessage() . '. Attempting fallback transport...');
            try {
                $username = config('mail.mailers.smtp.username') ?: env('MAIL_USERNAME');
                $password = config('mail.mailers.smtp.password') ?: env('MAIL_PASSWORD');
                $transport = (new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                    'smtp.gmail.com', 587, false
                ))
                ->setUsername($username)
                ->setPassword($password);

                if (method_exists($transport->getStream(), 'setSourceIp')) {
                    $transport->getStream()->setSourceIp('0.0.0.0');
                }

                $mailer = new \Illuminate\Mail\Mailer('fallback_smtp', app('view'), $transport);
                $mailer->alwaysFrom(config('mail.from.address') ?: 'urao70157@gmail.com', config('mail.from.name') ?: 'Blood Donor Network');

                $mailer->raw($text, function ($m) use ($adminEmail, $donorName, $donorGroup) {
                    $m->to($adminEmail)->subject("🔔 New Contact Request: {$donorName} ({$donorGroup})");
                });
                Log::info("Admin contact request email dispatched via fallback transport to {$adminEmail}");
            } catch (\Throwable $e2) {
                Log::error('Admin contact request notification email failed completely: ' . $e2->getMessage());
            }
        }
    }

    /**
     * Helper: Mediated notice to donor
     */
    private function notifyDonorOfApproval(DonorContactRequest $req): void
    {
        $donor = $req->donor;
        if (!$donor || empty($donor->email)) return;

        try {
            $text = "Assalam-o-Alaikum {$donor->name},\n\n"
                  . "An administrator has verified and approved a blood contact request for you:\n\n"
                  . "Patient: {$req->patient_name}\n"
                  . "Blood Group Needed: {$donor->blood_group}\n"
                  . "Hospital: {$req->hospital}, {$req->city}\n"
                  . "Urgency: {$req->urgency_level}\n"
                  . "Requester: {$req->requester_name}\n"
                  . "Phone / WhatsApp: {$req->requester_phone}\n\n"
                  . "Message from requester: \"{$req->message}\"\n\n"
                  . "If you are able to donate, please contact the requester directly. Thank you for saving lives!";

            Mail::raw($text, function ($m) use ($donor) {
                $m->to($donor->email)->subject("Approved Blood Contact Request - Blood Donor Network");
            });
        } catch (\Throwable $e) {
            Log::warning("Could not notify donor {$donor->email}: " . $e->getMessage());
        }
    }

    /**
     * Helper: Notice to requester
     */
    private function notifyRequesterOfApproval(DonorContactRequest $req): void
    {
        if (empty($req->requester_email)) return;

        try {
            $text = "Dear {$req->requester_name},\n\n"
                  . "Your request to contact blood donor ({$req->donor->blood_group}) for patient {$req->patient_name} has been APPROVED by the administrator.\n\n"
                  . "Admin Note: {$req->admin_notes}\n\n"
                  . "The donor has been notified with your patient and contact details. You can track this request at any time here:\n"
                  . route('donor-contacts.track', $req->tracking_token) . "\n\n"
                  . "Regards,\nBlood Donor Network Team";

            Mail::raw($text, function ($m) use ($req) {
                $m->to($req->requester_email)->subject("Your Donor Contact Request Has Been Approved!");
            });
        } catch (\Throwable $e) {
            Log::warning("Could not notify requester {$req->requester_email}: " . $e->getMessage());
        }
    }
}

