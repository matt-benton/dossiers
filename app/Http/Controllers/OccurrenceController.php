<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use Illuminate\Http\Request;
use Auth;

class OccurrenceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $words = explode(' ', $request->description);
        $people = Auth::user()->people()->select(['id', 'name'])->get();

        $selectedPeopleIds = [];
        $matchingPeople = [];
        $matchText = '';

        foreach ($words as $word) {
          if (substr($word, 0, 1) === '@') {
            // @ tells us this is the first word for selecting a person
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
            return str_contains(strtoupper($person->name), strtoupper($matchText));
          });

          // if we have 1 person left then that's our match
          if (count($matchingPeople) === 1) {
            $matchingPerson = $matchingPeople->first();
            array_push($selectedPeopleIds, $matchingPerson->id);
            $matchingPeople = [];
            $matchText = '';
          }
        }

        // ! (probably should add index to peoples names)

        // save the text as the occurrence description
        $occurrence = new Occurrence;
        $occurrence->description = $request->description;
        $occurrence->save();

        // attach people
        $occurrence->people()->attach($selectedPeopleIds);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
