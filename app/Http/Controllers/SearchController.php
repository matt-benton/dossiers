<?php

namespace App\Http\Controllers;

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
}
