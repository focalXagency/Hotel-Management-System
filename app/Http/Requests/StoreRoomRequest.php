<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            // 0-99: The room code starts with 1 or 2 digits representing the room number.
            // [A-D]?: An optional single uppercase letter, which might be used to designate a specific wing or section (e.g., A, B).
            // 0-15$: Ends with digits representing  the floor number.
            'code' => ['required','string','regex:/^([0-9]|[1-9][0-9])[A-D]?([0-9]|1[0-5])\b$/','max:100'],
            'floorNumber' => 'required|numeric|integer|max:15',
            'description' => 'required|string|max:800',
            'images' => 'array', // fot the field
            'images.*' => 'image|max:2048', // for the files themselves
            'status' => 'required|string|in:available,unavailable',
            'room_type'=>'exists:roomtypes,id',
        ];
    }
}

