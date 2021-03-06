<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Auth;
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
        'name' => 'required|max:75|alpha_num',
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
      $person->load(['occurrences' => function ($query) {
        $query->with('people:id,name')->orderBy('created_at', 'desc');
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
      return inertia('People/EditPerson')->with(['person' => $person]);
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
        'name' => 'required|max:255',
        'relationship' => 'max:255',
        'birthmonth' => 'nullable|between:1,12',
        'birthday' => 'nullable|between:1,31',
      ]);

      $person->name = $request->name;
      $person->relationship = $request->relationship;
      $person->birthmonth = $request->birthmonth;
      $person->birthday = $request->birthday;
      $person->save();

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
