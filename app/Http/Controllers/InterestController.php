<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveInterestRequest;
use App\Models\Interest;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use DB;
use App\Rules\AlphaNumSpace;

class InterestController extends Controller
{
  /**
   * Create the controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->authorizeResource(Interest::class, 'interest');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return inertia('Interests/Interests')->with([
        'interests' => Auth::user()->interests()->orderBy('name')->get()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return inertia('Interests/CreateInterest');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SaveInterestRequest $request)
    {
      $interest = new Interest;
      $interest->name = $request->name;
      Auth::user()->people()->save($interest);

      return Redirect::route('interests.edit', [$interest])->with('message', "{$interest->name} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function show(Interest $interest)
    {
      $interest->load(['threads' => function ($query) {
          $query->select(
            'threads.id',
            'threads.created_at',
            'threads.updated_at',
            DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'")
          );
          $query->with(['developments', 'people:id,name', 'interests:id,name']);
          $query->orderBy('last_development_at', 'desc');
        }, 'people' => function ($query) {
          $query->orderBy('name');
        }
      ]);

      return inertia('Interests/ShowInterest')->with(['interest' => $interest]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function edit(Interest $interest)
    {
      $interest->load(['people' => function ($query) {
        $query->orderBy('name');
      }]);

      // get people who do not have this interest
      $uninterested = Auth::user()
        ->people()
        ->with('interests')
        ->orderBy('name')
        ->get()
        ->filter(function ($person) use ($interest) {
          return $person->interests->doesntContain(function ($personInterests) use ($interest) {
            return $personInterests->id === $interest->id;
          });
        });

      return inertia('Interests/EditInterest', [
          'interest' => $interest,
          /**
           * Filter makes the collection an associative array
           * which becomes an object in the Vue component. We
           * have to use array_values to convert it to a
           * normal array.
           */
        'uninterested' => array_values($uninterested->all()),
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function update(SaveInterestRequest $request, Interest $interest)
    {
      $interest->name = $request->name;
      $interest->save();

      return redirect()->back()->with('message', "Info for {$request->name} updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Interest $interest)
    {
      $interest->delete();

      return redirect()->route('interests.index')->with('message', "{$interest->name} was removed.");
    }

    /**
     * Attach a person to the interest
     */
    public function addPerson(Request $request, Interest $interest)
    {
      $person = Auth::user()->people()->where('id', $request->personId)->first();

      $interest->people()->attach($person->id);

      return redirect()->back()->with('message', "{$person->name} is now interested in {$interest->name}");
    }

    /**
     * Remove a person from the interest
     */
    public function removePerson(Request $request, Interest $interest)
    {
      $person = Auth::user()->people()->where('id', $request->personId)->first();

      $interest->people()->detach($person->id);

      return redirect()->back()->with('message', "{$person->name} is no longer interested in {$interest->name}");
    }
}
