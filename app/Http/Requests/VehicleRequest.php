<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle');

        return [
            'driver_id' => ['nullable', 'exists:users,id'],
            'plate_number' => ['required','string','max:20',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId),
            ],
            'model' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', Rule::in(['truck', 'van', 'car', 'motorcycle'])],
            'status' => ['required', 'string', Rule::in(['idle', 'active', 'offline'])],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }
}