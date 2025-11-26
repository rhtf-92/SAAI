<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|string|max:45',
            'type' => 'nullable|string|max:45',
            'modality' => 'nullable|string|max:45',
            'focus' => 'nullable|string|max:45',
            'date' => 'nullable|date',
            'document_url' => 'nullable|string',
        ];
    }
}
