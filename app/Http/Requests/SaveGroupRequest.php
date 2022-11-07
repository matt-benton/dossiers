<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use Illuminate\Foundation\Http\FormRequest;

class SaveGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
              'required',
              'max:255',
              new AlphaNumSpace,
            ],
        ];
    }
}
