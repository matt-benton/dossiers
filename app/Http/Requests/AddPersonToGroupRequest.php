<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPersonToGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'personId' => 'required|numeric',
            'role' => 'max:30',
        ];
    }
}
