<?php

namespace App\Http\Controllers;

use App\Models\Development;
use App\Services\PersonService;
use App\Http\Requests\StoreDevelopmentRequest;
use App\Models\Thread;
use Auth;

class DevelopmentController extends Controller
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
        $this->authorizeResource(Development::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDevelopmentRequest $request)
    {
        $people = Auth::user()->people()->select(['id', 'name'])->get();

        $parsedPersonIds = $this->personService->findPeopleInString($request->description, $people);

        if ($request->filled('thread_id')) {
          $thread = Thread::findOrFail($request->thread_id);
        } else {
          $thread = new Thread;
          $thread->save();
        }

        $development = new Development;
        $development->description = $request->description;
        $thread->developments()->save($development);

        /**
         * Since threads relate to people instead of
         * developments, we have to add only people
         * who aren't already related to this thread.
         */
        $existingThreadPersonIds = $thread->people->pluck('id');
        $newThreadPersonIds = $parsedPersonIds->diff($existingThreadPersonIds);

        $thread->people()->attach($newThreadPersonIds->all());

        return redirect()->back()->with('message', "Event added successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Development  $development
     * @return \Illuminate\Http\Response
     */
    public function destroy(Development $development)
    {
      /**
       * If this is the only development in the thread
       * then delete the thread.
       */
      if (count($development->thread->developments) > 1) {
        $development->delete();
      } else {
        $development->thread->delete();
      }

      return redirect()->back()->with('message', 'Event deleted.');
    }
}
