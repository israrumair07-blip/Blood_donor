<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorContactRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id',
        'requester_name',
        'requester_phone',
        'requester_email',
        'patient_name',
        'hospital',
        'city',
        'urgency_level',
        'message',
        'status',
        'admin_notes',
        'tracking_token',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function getWhatsappNumberAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $this->requester_phone);
        if (str_starts_with($phone, '0')) {
            return '92' . substr($phone, 1);
        }
        return $phone;
    }
}

