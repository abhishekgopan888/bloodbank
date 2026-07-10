<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BloodBagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('blood_type') && !$this->has('blood_group')) {
            $this->merge([
                'blood_group' => $this->blood_type,
            ]);
        }

        if (!$this->has('quantity')) {
            $this->merge([
                'quantity' => 1,
            ]);
        }

        if (!$this->has('status')) {
            $this->merge([
                'status' => 'Available',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'bag_number' => ['required', 'string'],
            'blood_group' => ['required', 'string'],
            'blood_type' => ['nullable', 'string'],
            'donor_name' => ['nullable', 'string'],
            'collection_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'in:Available,Reserved,Dispatched,Expired'],
            'refrigerator_id' => ['nullable', 'exists:refrigerators,id'],
        ];
    }
}

