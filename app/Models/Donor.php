<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'gender', 'blood_group', 'city',
        'is_available', 'notify_urgent_requests', 'last_donated_at', 'donor_token',
        'guardian_name', 'guardian_relation', 'guardian_phone',
        'donation_pending',
    ];

    protected $casts = [
        'is_available'           => 'boolean',
        'notify_urgent_requests' => 'boolean',
        'donation_pending'       => 'boolean',
        'last_donated_at'        => 'date',
    ];

    public function scopeAvailableForUrgentAlert($query, string $bloodGroup, string $city)
    {
        $cleanCity  = trim($city);
        $cleanGroup = trim($bloodGroup);

        return $query->where('is_available', true)
            ->where(function ($q) {
                $q->where('notify_urgent_requests', true)
                  ->orWhereNull('notify_urgent_requests');
            })
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where('blood_group', $cleanGroup)
            ->where(function ($q) use ($cleanCity) {
                $q->where('city', 'LIKE', '%' . $cleanCity . '%')
                  ->orWhereRaw('LOWER(TRIM(city)) = ?', [strtolower($cleanCity)]);
            })
            ->where(function ($q) {
                $q->whereNull('last_donated_at')
                  ->orWhere('last_donated_at', '<=', now()->subDays(90)->toDateString());
            });
    }

    protected $hidden = ['donor_token'];

    public function contactRequests()
    {
        return $this->hasMany(DonorContactRequest::class);
    }

    public function getMaskedPhoneAttribute(): string
    {
        $phone = (string) $this->phone;
        if (strlen($phone) <= 4) {
            return '****';
        }
        return substr($phone, 0, 3) . '****' . substr($phone, -2);
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