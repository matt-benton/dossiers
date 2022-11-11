<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Interest;
use Auth;
use Illuminate\Http\Request;

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

    public function groups(Request $request)
    {
        $name = $request->query('name', '');

        $query = Auth::user()->groups();

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        $groups = $query->orderBy('name')->get();

        return response()->json(['groups' => $groups]);
    }

    public function uninterested(Request $request, Interest $interest)
    {
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

    public function nonMembers(Request $request, Group $group)
    {
        $name = $request->query('name', '');

        $query = Auth::user()
          ->people()
          ->with('groups')
          ->orderBy('name');

        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }

        $nonMembers = $query->get()->filter(function ($person) use ($group) {
            return $person->groups->doesntContain(function ($personGroups) use ($group) {
                return $personGroups->id === $group->id;
            });
        });

        return response()->json(['nonMembers' => array_values($nonMembers->all())]);
    }
}
