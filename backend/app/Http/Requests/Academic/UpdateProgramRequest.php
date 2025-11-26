<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:45|unique:programs,code,' . $this->program->id,
            'name' => 'required|string|max:350',
            'level' => 'required|string|max:45',
        ];
    }
}
