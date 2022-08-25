<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class SearchStringService
{
  public function findPeopleInString(string $str, Collection $people): SupportCollection
  {
    $words = explode(' ', $str);

    $selectedPeopleIds = [];
    $matchingPeople = [];
    $matchText = '';

    foreach ($words as $word) {
      if (substr($word, 0, 1) === '@') {
        // @ tells us this is the first letter for selecting a person
        $matchText = substr($word, 1);
      } else if (count($matchingPeople) > 0) {
        // previous words have failed to narrow down a match
        // so we are adding the current word to the string
        $matchText .= " {$word}";
      } else {
        // we are not currently in a person search string
        continue;
      }

      // filter people based on our string of text
      $matchingPeople = $people->filter(function ($person) use ($matchText) {
        $possibleNames = $person->getPossibleNames();

        $possibleMatches = array_filter($possibleNames, function ($pName) use ($matchText) {
          return str_contains(strtoupper($pName), strtoupper($matchText));
        });

        return count($possibleMatches) > 0;
      });

      // if we have 1 person left then that's our match
      if (count($matchingPeople) === 1) {
        $matchingPerson = $matchingPeople->first();
        array_push($selectedPeopleIds, $matchingPerson->id);
        $matchingPeople = [];
        $matchText = '';
      }
    }

    return collect($selectedPeopleIds);
  }

  public function findInterestsInString(string $str, Collection $interests): SupportCollection
  {
    $words = explode(' ', $str);

    $selectedInterestIds = [];
    $matchingInterests = [];
    $matchText = '';

    foreach ($words as $word) {
      if (substr($word, 0, 1) === '@') {
        // @ tells us this is the first letter for selecting an interest
        $matchText = substr($word, 1);
      } else if (count($matchingInterests) > 0) {
        // previous words have failed to narrow down a match
        // so we are adding the current word to the string
        $matchText .= " {$word}";
      } else {
        // we are not currently in a person search string
        continue;
      }

      // filter interests based on our string of text
      $matchingInterests = $interests->filter(function ($interest) use ($matchText) {
        $possibleNames = $interest->getPossibleNames();

        $possibleMatches = array_filter($possibleNames, function ($pName) use ($matchText) {
          return str_contains(strtoupper($pName), strtoupper($matchText));
        });

        return count($possibleMatches) > 0;
      });

      // if we have 1 interest left then that's our match
      if (count($matchingInterests) === 1) {
        $matchingInterest = $matchingInterests->first();
        array_push($selectedInterestIds, $matchingInterest->id);
        $matchingInterests = [];
        $matchText = '';
      }
    }

    return collect($selectedInterestIds);
  }
}
