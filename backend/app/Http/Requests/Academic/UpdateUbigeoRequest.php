<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUbigeoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ubigeo' => 'required|string|size:6|unique:ubigeos,ubigeo,' . $this->ubigeo->ubigeo . ',ubigeo',
            'department' => 'required|string|max:45',
            'province' => 'required|string|max:45',
            'district' => 'required|string|max:45',
        ];
    }
}
