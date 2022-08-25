<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

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
      $threads = Auth::user()->threads()->with([
        'developments', 'people:id,name', 'interests:id,name'
      ])
      ->select(
        'threads.id',
        'threads.created_at',
        'threads.updated_at',
        DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
      )
      ->orderBy('last_development_at', 'desc')
      ->get();

      return inertia('Dashboard')->with(['threads' => $threads]);
    }
}
