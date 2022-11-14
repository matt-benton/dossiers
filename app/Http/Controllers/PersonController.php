<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Rules\AlphaNumSpace;
use Auth;
use DB;
use Illuminate\Http\Request;
use Redirect;

class PersonController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class, 'person');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('People/People')->with([
            'people' => Auth::user()->people()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('People/CreatePerson');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:75', new AlphaNumSpace],
            'relationship' => 'max:255',
            'birthmonth' => 'nullable|between:1,12',
            'birthday' => 'nullable|between:1,31',
        ]);

        $person = new Person;
        $person->name = $request->name;
        $person->relationship = $request->relationship;
        $person->birthmonth = $request->birthmonth;
        $person->birthday = $request->birthday;
        Auth::user()->people()->save($person);

        return Redirect::route('people.edit', $person)->with('message', "{$person->name} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        // get the person's interests
        $person->load([
          'interests' => function ($query) {
            $query->orderBy('name');
          },
          'groups' => function ($query) {
            $query->with('members')->orderBy('name');
          }
        ]);

        // get threads about this person
        $personThreads = $person->threads()->select(
            'threads.id',
            'threads.created_at',
            'threads.updated_at',
            DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
        )
          ->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name'])
          ->get();

        // get threads for things this person is interested in
        $interestThreads = $person->interests()->with(['threads' => function ($query) {
            $query->select(
                'threads.id',
                'threads.created_at',
                'threads.updated_at',
                DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
            );
            $query->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name']);
        }])
          ->get()
          ->flatMap(function ($interest) {
              return $interest->threads;
          });

        $groups = $person->groups()->with(['threads' => function ($query) {
            $query->select(
                'threads.id',
                'threads.created_at',
                'threads.updated_at',
                DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
            );
            $query->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name']);
        }, 'members'])
          ->get();

        // get threads for groups this person is in
        $groupThreads = $groups->flatMap(function ($group) {
            return $group->threads;
        });

        // get threads for people who are in the same groups as this person
        $groupedIds = $groups->flatMap(function ($group) {
            return $group->members;
        })->pluck('id');

        $grouped = Person::whereIn('id', $groupedIds)->with(['threads' => function ($query) {
            $query->select(
                'threads.id',
                'threads.created_at',
                'threads.updated_at',
                DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
            );
            $query->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name']);
        }])
          ->get();

        $groupMemberThreads = $grouped->flatMap(function ($person) {
            return $person->threads;
        });

        // combine threads about the person with threads the person is interested in
        $combinedThreads = $personThreads
          ->concat($interestThreads)
          ->concat($groupThreads)
          ->concat($groupMemberThreads)
          ->sortByDesc('last_development_at')
          ->unique('id');

        $threads = $combinedThreads->values()->all();

        return inertia('People/ShowPerson')->with([
            'person' => $person,
            'threads' => $threads,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        return inertia('People/EditPerson')->with([
            'person' => $person->load('interests', 'groups'),
            'interests' => Auth::user()->interests()->orderBy('name')->get(),
            'groups' => Auth::user()->groups()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'name' => ['required', 'max:75', new AlphaNumSpace],
            'relationship' => 'max:255',
            'birthmonth' => 'nullable|between:1,12',
            'birthday' => 'nullable|between:1,31',
            'interest_ids' => 'array',
            'interest_ids.*' => 'integer',
            'group_ids' => 'array',
            'group_ids.*' => 'integer',
        ]);

        DB::transaction(function () use ($person, $request) {
            $developments = $person->threads->flatMap(function ($thread) {
                return $thread->developments;
            });

            foreach ($developments as $dev) {
                $dev->description = str_replace("@{$person->name}", "@{$request->name}", $dev->description);
                $dev->save();
            }

            $person->name = $request->name;
            $person->relationship = $request->relationship;
            $person->birthmonth = $request->birthmonth;
            $person->birthday = $request->birthday;
            $person->save();

            $person->interests()->sync($request->interest_ids);
            $person->groups()->sync($request->group_ids);
        });

        return redirect()->back()->with('message', "Info for {$request->name} updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('people.index')->with('message', "{$person->name} was removed.");
    }
}
