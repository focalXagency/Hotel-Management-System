<?php

namespace App\Http\Requests\Api;

use App\Http\Traits\ApiResponserTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    use ApiResponserTrait;
    
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
        $rules = [
            'phone' => 'required|numeric|unique:contacts,phone',
        ];
        # determine the type of authentication for consider the gurad api which is sanctum.
        if (!auth('sanctum')->check()) {
            $rules['name'] = 'required|max:30';
            $rules['email'] = 'required|email|unique:contacts,email';
        }
    
        return $rules;
    }
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();
        throw new HttpResponseException(
            $this->errorResponse('Validation failed.', $errors->toArray(), 422)
        );
    }
}
