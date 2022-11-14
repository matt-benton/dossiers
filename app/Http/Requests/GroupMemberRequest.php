<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Person;

class GroupMemberRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', Person::find($this->personId));
    }

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
