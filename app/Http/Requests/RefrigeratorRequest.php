<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefrigeratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if (!$this->has('identifier')) {
            if ($this->has('name') && $this->has('model')) {
                $this->merge([
                    'identifier' => $this->input('name') . ' (' . $this->input('model') . ')',
                ]);
            } elseif ($this->has('name')) {
                $this->merge([
                    'identifier' => $this->input('name'),
                ]);
            } elseif ($this->has('model')) {
                $this->merge([
                    'identifier' => $this->input('model'),
                ]);
            }
        }

        if (!$this->has('status')) {
            $this->merge([
                'status' => 'active',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'name' => ['nullable', 'string'],
            'model' => ['nullable', 'string'],
            'blood_bank_id' => ['nullable', 'exists:blood_banks,id'],
            'status' => ['sometimes', 'string'],
        ];
    }
}

