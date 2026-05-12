<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_no' => $this->admission_no,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'class' => $this->class->name,
            'section' => $this->section->name,
            'phone' => $this->phone,
            'gender' => $this->gender,
        ];
    }
}
