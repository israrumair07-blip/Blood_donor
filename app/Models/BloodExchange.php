<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'offered_blood_group',
        'needed_blood_group',
        'units',
        'patient_name',
        'hospital',
        'city',
        'contact_name',
        'contact_phone',
        'contact_email',
        'urgency_level',
        'notes',
        'status',
        'admin_notes',
        'manage_token',
        'reviewed_at',
        'completed_at',
    ];

    protected $casts = [
        'units'        => 'integer',
        'reviewed_at'  => 'datetime',
        'completed_at' => 'datetime',
    ];

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
        $phone = preg_replace('/[^0-9]/', '', (string) $this->contact_phone);
        if (str_starts_with($phone, '0')) {
            return '92' . substr($phone, 1);
        }
        return $phone;
    }
}

