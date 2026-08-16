<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleUpdateRequest extends FormRequest
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
            'plate_number' => ['nullable', 'string', 'max:20', Rule::unique('vehicles', 'plate_number')->ignore($vehicleId)],
            'model' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', Rule::in(['truck', 'van', 'car', 'motorcycle'])],
            'status' => ['nullable', 'string', Rule::in(['idle', 'active', 'offline'])],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }
}