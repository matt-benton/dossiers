<?php

namespace App\Http\Controllers;

use App\Models\Occurrence;
use App\Services\PersonService;
use App\Http\Requests\StoreOccurrenceRequest;
use Auth;

class OccurrenceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  PersonService  $personService
     * @return void
     */
    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
        $this->authorizeResource(Occurrence::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOccurrenceRequest $request)
    {
        $people = Auth::user()->people()->select(['id', 'name'])->get();

        $parsedPersonIds = $this->personService->findPeopleInString($request->description, $people);

        // ! (probably should add index to peoples names)

        // save the text as the occurrence description
        $occurrence = new Occurrence;
        $occurrence->description = $request->description;
        $occurrence->save();

        // attach people
        $occurrence->people()->attach($parsedPersonIds);

        return redirect()->back()->with('message', "Event added successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Occurrence  $occurrence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Occurrence $occurrence)
    {
      $occurrence->delete();

      return redirect()->back()->with('message', 'Event deleted.');
    }
}
