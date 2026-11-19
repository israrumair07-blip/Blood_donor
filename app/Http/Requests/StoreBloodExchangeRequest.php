<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodExchangeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offered_blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'needed_blood_group'  => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-|different:offered_blood_group',
            'units'               => 'required|integer|min:1|max:20',
            'patient_name'        => 'required|string|max:100',
            'hospital'            => 'required|string|max:150',
            'city'                => 'required|string|max:100',
            'contact_name'        => 'required|string|max:100',
            'contact_phone'       => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'contact_email'       => 'nullable|email|max:150',
            'urgency_level'       => 'required|string|max:50',
            'notes'               => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'offered_blood_group.required' => 'Please select the blood group you have available to give.',
            'needed_blood_group.required'  => 'Please select the blood group you need in return.',
            'needed_blood_group.different' => 'The needed blood group must be different from the offered blood group.',
            'patient_name.required'        => 'Please provide the patient name.',
            'hospital.required'            => 'Hospital name or location is required.',
            'city.required'                => 'City is required.',
            'contact_name.required'        => 'Contact person name is required.',
            'contact_phone.required'       => 'Contact phone number is required.',
        ];
    }
}

