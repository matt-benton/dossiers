<?php

namespace App\Http\Requests;

use App\Rules\HasPersonOrInterest;
use App\Services\SearchStringService;
use Illuminate\Foundation\Http\FormRequest;

class StoreDevelopmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(SearchStringService $searchStringService)
    {
        return [
            'thread_id' => 'exists:threads,id',
            'description' => [
                'required',
                'max:255',
                'string',
                new HasPersonOrInterest($searchStringService),
                'regex:/^[^<>*^{}|[\]]+$/',
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
            'description.regex' => 'Some special characters are not allowed.',
        ];
    }
}
