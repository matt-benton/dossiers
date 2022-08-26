<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Rules\AlphaNumSpace;
use Illuminate\Http\Request;
use Auth;
use Redirect;
use DB;

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
        'people' => Auth::user()->people()->orderBy('name')->get()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return inertia('People/CreatePerson')->with([
        'interests' => Auth::user()->interests()->orderBy('name')->get(),
      ]);
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
        'interest_ids' => 'array',
        'interest_ids.*' => 'integer',
      ]);

      $person = new Person;
      $person->name = $request->name;
      $person->relationship = $request->relationship;
      $person->birthmonth = $request->birthmonth;
      $person->birthday = $request->birthday;
      Auth::user()->people()->save($person);

      $person->interests()->sync($request->interest_ids);

      return Redirect::route('people.index')->with('message', "{$person->name} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
      $person->load(['threads' => function ($query) {
        $query->select(
          'threads.id',
          'threads.created_at',
          'threads.updated_at',
          DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"));
        $query->with(['developments', 'people:id,name', 'interests:id,name']);
        $query->orderBy('last_development_at', 'desc');
      },
      'interests' => function ($query) {
        $query->orderBy('name');
      }]);

      return inertia('People/ShowPerson')->with(['person' => $person]);
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
        'person' => $person->load('interests'),
        'interests' => Auth::user()->interests()->orderBy('name')->get(),
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
        'name' => 'required|max:75',
        'relationship' => 'max:255',
        'birthmonth' => 'nullable|between:1,12',
        'birthday' => 'nullable|between:1,31',
        'interest_ids' => 'array',
        'interest_ids.*' => 'integer',
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
