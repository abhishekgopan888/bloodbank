<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefrigeratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'blood_bank_id' => ['nullable', 'exists:blood_banks,id'],
            'status' => ['nullable', 'string'],
        ];
    }
}
