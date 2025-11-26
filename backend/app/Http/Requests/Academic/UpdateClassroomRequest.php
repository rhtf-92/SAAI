<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|max:45',
            'number' => 'required|string|max:45',
            'floor' => 'required|string|max:45',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'observation' => 'nullable|string',
        ];
    }
}
