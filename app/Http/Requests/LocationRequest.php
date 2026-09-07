<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'heading' => ['nullable', 'numeric', 'between:0,360'],
            'recorded_at' => ['nullable', 'date'],
        ];
    }
}