<?php

namespace App\Rules;

use App\Services\SearchStringService;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class HasPersonOrInterestOrGroup implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(SearchStringService $searchStringService)
    {
        $this->searchStringService = $searchStringService;
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
        // value must contain a name matching a person or interest this user has added
        $parsedPersonIds = $this->searchStringService->findPeopleInString($value, Auth::user()->people);
        $parsedInterestIds = $this->searchStringService->findInterestsInString($value, Auth::user()->interests);
        $parsedGroupIds = $this->searchStringService->findGroupsInString($value, Auth::user()->groups);

        return (count($parsedPersonIds) + count($parsedInterestIds) + count($parsedGroupIds)) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A person, interest, or group must be tagged in the text.';
    }
}
