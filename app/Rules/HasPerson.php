<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\PersonService;
use Auth;

class HasPerson implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(PersonService $personService)
    {
      $this->personService = $personService;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // value must contain a name matching a person this user has added
        $parsedPersonIds = $this->personService->findPeopleInString($value, Auth::user()->people);

        return count($parsedPersonIds) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person must be tagged in the text.';
    }
}
