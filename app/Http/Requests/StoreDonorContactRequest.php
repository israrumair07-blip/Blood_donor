<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonorContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donor_id'        => 'required|exists:donors,id',
            'requester_name'  => 'required|string|max:100',
            'requester_phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'requester_email' => 'required|email|max:150',
            'patient_name'    => 'required|string|max:100',
            'hospital'        => 'required|string|max:150',
            'city'            => 'required|string|max:100',
            'urgency_level'   => 'required|string|max:50',
            'message'         => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'donor_id.required'        => 'Donor selection is required.',
            'requester_name.required'  => 'Please enter your full name.',
            'requester_phone.required' => 'Please enter your phone number.',
            'requester_email.required' => 'Please enter your email so the admin can send you updates.',
            'patient_name.required'    => 'Please enter the patient name.',
            'hospital.required'        => 'Hospital or clinic location is required.',
            'city.required'            => 'City is required.',
            'message.required'         => 'Please explain the reason for contacting this donor.',
        ];
    }
}

