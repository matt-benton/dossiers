<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveInterestRequest extends FormRequest
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
                'max:50',
                new AlphaNumSpace,
                // interest must be unique for this user
                Rule::unique('interests')
                  ->where(fn ($query) => $query->where('user_id', Auth::user()->id))
                  ->ignore($this->interest ? $this->interest->id : null),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'That interest already exists.',
        ];
    }
}
