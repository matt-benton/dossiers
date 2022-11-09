<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class SearchStringService
{
    public function findPeopleInString(string $str, Collection $people): SupportCollection
    {
        $selectedPeopleIds = $people->filter(function ($person) use ($str) {
            return preg_match("/@{$person->name}/", $str);
        })->map(function ($filtered) {
            return $filtered->id;
        });

        return collect($selectedPeopleIds);
    }

    public function findInterestsInString(string $str, Collection $interests): SupportCollection
    {
        $selectedInterestIds = $interests->filter(function ($interest) use ($str) {
            return preg_match("/@{$interest->name}/", $str);
        })->map(function ($filtered) {
            return $filtered->id;
        });

        return collect($selectedInterestIds);
    }

    public function findGroupsInString(string $str, Collection $groups): SupportCollection
    {
        $selectedGroupIds = $groups->filter(function ($group) use ($str) {
            return preg_match("/@{$group->name}/", $str);
        })->map(function ($filtered) {
            return $filtered->id;
        });

        return collect($selectedGroupIds);
    }
}
