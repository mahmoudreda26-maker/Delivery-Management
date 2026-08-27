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
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'speed' => ['required', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'heading' => ['required', 'numeric', 'between:0,360'],
            'recorded_at' => ['required', 'date'],
        ];
    }
}