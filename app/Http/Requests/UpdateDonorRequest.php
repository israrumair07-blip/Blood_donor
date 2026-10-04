<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDonorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $donor = $this->route('donor');
        $donorId = $donor instanceof \App\Models\Donor ? $donor->id : $donor;

        return [
            'name'        => 'required|string|max:100',
            'phone'       => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/', Rule::unique('donors', 'phone')->ignore($donorId)],
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'city'        => 'required|string|max:100',
            'gender'            => 'required|in:Male,Female,Other',
            'guardian_name'     => 'nullable|required_if:gender,Female|string|max:100',
            'guardian_relation' => 'nullable|required_if:gender,Female|string|max:50',
            'guardian_phone'    => ['nullable', 'required_if:gender,Female', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{7,20}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique'                  => 'This phone number is already registered.',
            'phone.regex'                   => 'Phone number can only contain digits, +, - and spaces.',
            'guardian_phone.regex'          => 'Guardian phone can only contain digits, +, - and spaces.',
            'guardian_name.required_if'     => 'Guardian name is required for female donors.',
            'guardian_relation.required_if' => 'Guardian relation is required for female donors.',
            'guardian_phone.required_if'    => 'Guardian phone is required for female donors.',
        ];
    }
}