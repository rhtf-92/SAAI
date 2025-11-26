<?php

namespace App\Http\Resources\Academic;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UbigeoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ubigeo' => $this->ubigeo,
            'department' => $this->department,
            'province' => $this->province,
            'district' => $this->district,
            'label' => "{$this->department} - {$this->province} - {$this->district}",
        ];
    }
}
