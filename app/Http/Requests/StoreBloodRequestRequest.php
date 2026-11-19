<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name'  => 'required|string|max:100',
            'blood_group'   => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_needed'  => 'required|integer|min:1|max:20',
            'hospital'      => 'required|string|max:150',
            'city'          => 'required|string|max:100',
            'contact_name'  => 'required|string|max:100',
            'contact_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'urgency_level' => 'required|string|max:50',
            'notes'         => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_name.required'  => 'Please provide the patient name.',
            'blood_group.required'   => 'Please select the required blood group.',
            'hospital.required'      => 'Hospital name or location is required.',
            'city.required'          => 'City is required to notify nearby donors.',
            'contact_name.required'  => 'Contact person name is required.',
            'contact_phone.required' => 'Contact phone number is required.',
            'contact_phone.regex'    => 'Please enter a valid phone number.',
        ];
    }
}

