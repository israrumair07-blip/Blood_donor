<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'gender', 'blood_group', 'city',
        'is_available', 'last_donated_at', 'donor_token',
        'guardian_name', 'guardian_relation', 'guardian_phone',
        'donation_pending',
    ];

    protected $casts = [
        'is_available'     => 'boolean',
        'donation_pending' => 'boolean',
        'last_donated_at'  => 'date',
    ];

    protected $hidden = ['donor_token'];

    public function getWhatsappNumberAttribute()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (str_starts_with($phone, '0')) {
            return '92' . substr($phone, 1);
        }
        return $phone;
    }

    public function getIsEligibleAttribute()
    {
        if (empty($this->last_donated_at)) {
            return true;
        }
        return Carbon::parse($this->last_donated_at)->addDays(90)->isPast();
    }

    public function getDisplayPhoneAttribute()
    {
        return ($this->gender === 'Female' && $this->guardian_phone)
            ? $this->guardian_phone
            : $this->phone;
    }

    public function getDisplayContactNameAttribute()
    {
        return $this->guardian_name
            ? $this->guardian_name . ' (' . $this->guardian_relation . ')'
            : $this->name;
    }
}