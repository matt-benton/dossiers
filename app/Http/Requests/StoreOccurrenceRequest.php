<?php

namespace App\Http\Requests;

use App\Services\PersonService;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\HasPerson;

class StoreOccurrenceRequest extends FormRequest
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
    public function rules(PersonService $personService)
    {
        return [
            'description' => ['required', 'max:255', 'string', new HasPerson($personService)],
        ];
    }
}
