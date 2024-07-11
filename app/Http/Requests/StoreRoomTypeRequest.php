<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends FormRequest
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
            'name'         => ['string', 'required'],
            'price'        => ['numeric', 'required','between:1,99999999.99'],
            'capacity'     => ['integer', 'required', 'between:2,6'],
            'description'  => ['string', 'required', 'max:200'],
            'service_id'   =>[ 'required','array'],
            'service_id.*' => ['exists:services,id'],

        ];
    }
}
