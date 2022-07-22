<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ApiPersonController extends Controller
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
    public function index(Request $request)
    {
      $name = $request->query('name', '');
      $relationship = $request->query('relationship', '');

      $query = Auth::user()->people();

      if ($name) {
        $query->where('name', 'like', "%{$name}%");
      }

      if ($relationship) {
        $query->orWhere('relationship', 'like', "%{$relationship}%");
      }

      $people = $query->orderBy('name')->get();

      return response()->json(['people' => $people]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
