<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Occurrence;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
      $occurrences = Occurrence::select(
          'occurrences.id',
          'occurrences.description',
          'occurrences.created_at',
        )
        ->with('people:id,name')
        ->join('occurrentables', 'occurrences.id', '=', 'occurrentables.occurrence_id')
        ->join('people', 'people.id', '=', 'occurrentables.occurrentable_id')
        ->where('occurrentables.occurrentable_type', 'App\Models\Person')
        ->where('people.user_id', Auth::user()->id)
        ->distinct()
        ->orderBy('occurrences.created_at', 'desc')
        ->get();

      return inertia('Dashboard')->with(['occurrences' => $occurrences]);
    }
}
