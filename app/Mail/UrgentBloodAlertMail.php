<?php

namespace App\Mail;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UrgentBloodAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BloodRequest $bloodRequest,
        public Donor $donor
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🚨 URGENT: {$this->bloodRequest->blood_group} Blood Needed in {$this->bloodRequest->city} ({$this->bloodRequest->hospital})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.urgent_blood_alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

