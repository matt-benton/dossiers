<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Thread;

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
      $threads = Thread::with(['developments', 'people:id,name'])
        ->select(
          'threads.id',
          'threads.created_at',
          'threads.updated_at'
          )
        ->join('threadables', 'threadables.thread_id', '=', 'threads.id')
        ->join('people', 'people.id', '=', 'threadables.threadable_id')
        ->where('threadables.threadable_type', 'App\Models\Person')
        ->where('people.user_id', Auth::user()->id)
        ->distinct()
        ->get();

      return inertia('Dashboard')->with(['threads' => $threads]);
    }
}
