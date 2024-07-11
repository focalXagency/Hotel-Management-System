<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicesRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:100|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'price' => 'required|numeric|between:0,9999.99',
            'description' => 'required|string|max:800|regex:/^(?=.*[a-zA-Z])[a-zA-Z0-9\s\-]+$/',
            'img' => 'required|image|mimes:jpg,png,jpeg,gif',
        ];
    }
}
