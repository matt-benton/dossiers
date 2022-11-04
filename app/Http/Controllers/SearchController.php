<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use Auth;

class SearchController extends Controller
{
    public function people(Request $request)
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

    public function interests(Request $request)
    {
      $name = $request->query('name', '');

      $query = Auth::user()->interests();

      if ($name) {
        $query->where('name', 'like', "%{$name}%");
      }

      $interests = $query->orderBy('name')->get();

      return response()->json(['interests' => $interests]);
    }

    public function uninterested(Request $request, Interest $interest) {
      $name = $request->query('name', '');

      $query = Auth::user()
        ->people()
        ->with('interests')
        ->orderBy('name');

      if ($name) {
        $query->where('name', 'like', "%{$name}%");
      }

      $uninterested = $query
        ->get()
        ->filter(function ($person) use ($interest) {
          return $person->interests->doesntContain(function ($personInterests) use ($interest) {
            return $personInterests->id === $interest->id;
          });
        });

      return response()->json(['uninterested' => array_values($uninterested->all())]);
    }
}
