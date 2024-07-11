<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        $guest = $this->route('guest'); // Retrieve the guest ID from the route parameter
        return [
            'name' => 'nullable|string|max:100',
            'birthDate' => 'nullable|date',
            'phone_number' => 'nullable|string|regex:/^[0-9]{10}$/',
            'identificationNumber' => 'nullable|string|max:50|unique:guests,identificationnumber,'.$guest->id,
            'reservations' => 'required|array',
            'reservations.*' => 'integer|exists:reservations,id',
        ]; 
    }
    // add a custom validation rule that checks if there are any duplicate reservation IDs in the reservations array.
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $reservations = $this->input('reservations');
            if (count($reservations) !== count(array_unique($reservations))) {
                $validator->errors()->add('reservations', 'Duplicate reservations are not allowed.');
            }
        });
    }
}
