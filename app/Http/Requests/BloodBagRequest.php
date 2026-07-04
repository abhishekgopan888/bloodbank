<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BloodBagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bag_number' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'donor_name' => ['nullable', 'string'],
            'collection_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'quantity' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Available,Reserved,Dispatched,Expired'],
            'refrigerator_id' => ['nullable', 'exists:refrigerators,id'],
        ];
    }
}
