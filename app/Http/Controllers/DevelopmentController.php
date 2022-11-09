<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDevelopmentRequest;
use App\Models\Development;
use App\Models\Thread;
use App\Services\SearchStringService;
use Auth;

class DevelopmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  SearchStringService  $searchStringService
     * @return void
     */
    public function __construct(SearchStringService $searchStringService)
    {
        $this->searchStringService = $searchStringService;
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
        $interests = Auth::user()->interests()->select(['id', 'name'])->get();
        $groups = Auth::user()->groups()->select(['id', 'name'])->get();

        $parsedPersonIds = $this->searchStringService->findPeopleInString($request->safe()->description, $people);
        $parsedInterestIds = $this->searchStringService->findInterestsInString($request->safe()->description, $interests);
        $parsedGroupIds = $this->searchStringService->findGroupsInString($request->safe()->description, $groups);

        if ($request->filled('thread_id')) {
            $thread = Thread::findOrFail($request->thread_id);
        } else {
            $thread = new Thread;
            Auth::user()->threads()->save($thread);
        }

        $development = new Development;
        $development->description = $request->description;
        $thread->developments()->save($development);

        /**
         * Since threads relate to people/interests/groups instead of
         * developments, we have to add only people/interests/groups
         * who aren't already related to this thread.
         */
        $existingThreadPersonIds = $thread->people->pluck('id');
        $existingInterestIds = $thread->interests->pluck('id');
        $existingGroupIds = $thread->groups->pluck('id');
        $newThreadPersonIds = $parsedPersonIds->diff($existingThreadPersonIds);
        $newThreadInterestIds = $parsedInterestIds->diff($existingInterestIds);
        $newThreadGroupIds = $parsedGroupIds->diff($existingGroupIds);

        $thread->people()->attach($newThreadPersonIds->all());
        $thread->interests()->attach($newThreadInterestIds->all());
        $thread->groups()->attach($newThreadGroupIds->all());

        return redirect()->back()->with('message', 'Event added successfully.');
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
