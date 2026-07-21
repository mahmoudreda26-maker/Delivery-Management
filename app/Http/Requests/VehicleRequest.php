<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       $vehicleId = $this->route('id'); 
        return [
            'driver_id'    => ['nullable', 'exists:users,id'],
            'plate_number' => ['required', 'string', 'max:20', 
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId, 'id')
            ],
            'model'        => ['required', 'string', 'max:100'],
            'type'         => ['required', 'string', Rule::in(['truck', 'van', 'car', 'motorcycle'])], 
            'status'       => ['nullable', 'string', Rule::in(['idle', 'active', 'offline'])],
            'year'         => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }
}
