<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use App\Rules\AlphaNumSpace;
use Illuminate\Http\Request;
use Auth;
use Redirect;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => ['required', 'max:75', new AlphaNumSpace],
      ]);

      $interest = new Interest;
      $interest->name = $request->name;
      Auth::user()->people()->save($interest);

      return Redirect::route('interests.index')->with('message', "{$interest->name} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function show(Interest $interest)
    {
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Interest $interest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Interest  $interest
     * @return \Illuminate\Http\Response
     */
    public function destroy($interest)
    {
        //
    }
}
