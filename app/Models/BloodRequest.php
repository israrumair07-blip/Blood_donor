<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'blood_group',
        'units_needed',
        'hospital',
        'city',
        'contact_name',
        'contact_phone',
        'urgency_level',
        'notes',
        'status',
        'manage_token',
        'fulfilled_at',
    ];

    protected $casts = [
        'units_needed' => 'integer',
        'fulfilled_at' => 'datetime',
    ];

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function getIsOpenAttribute(): bool
    {
        return $this->status === 'open';
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

