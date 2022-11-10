<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPersonToGroupRequest;
use App\Http\Requests\SaveGroupRequest;
use App\Models\Group;
use Auth;
use DB;
use Illuminate\Http\Request;
use Redirect;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Groups/Groups', [
            'groups' => Auth::user()->groups()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Groups/CreateGroup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveGroupRequest $request)
    {
        $group = new Group;
        $group->name = $request->safe()->name;
        Auth::user()->groups()->save($group);

        return Redirect::route('groups.edit', [$group])
          ->with('message', "{$group->name} added successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $group->load([
            'people' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        // get threads about this group
        $groupThreads = $group->threads()->select(
            'threads.id',
            'threads.created_at',
            'threads.updated_at',
            DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
        )
        ->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name'])
        ->get();

        // get threads for people in this group
        $personThreads = $group->people()->with(['threads' => function ($query) {
            $query->select(
                'threads.id',
                'threads.created_at',
                'threads.updated_at',
                DB::raw("(select max(developments.created_at) from developments where developments.thread_id = threads.id) as 'last_development_at'"),
            );
            $query->with(['developments', 'people:id,name', 'interests:id,name', 'groups:id,name']);
        }])
        ->get()
        ->flatMap(function ($interest) {
            return $interest->threads;
        });

        $combinedThreads = $groupThreads->concat($personThreads)->sortByDesc('last_development_at')->unique('id');
        $threads = $combinedThreads->values()->all();

        return inertia('Groups/ShowGroup', [
            'group' => $group,
            'threads' => $threads,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $group->load(['people' => function ($query) {
            $query->orderBy('name');
        }]);

        $groupedPeopleIds = $group->people->pluck('id');

        $ungrouped = Auth::user()->people()->whereNotIn('id', $groupedPeopleIds)->orderBy('name')->get();

        return inertia('Groups/EditGroup', [
            'group' => $group,
            'ungrouped' => $ungrouped,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(SaveGroupRequest $request, Group $group)
    {
        $group->name = $request->safe()->name;
        $group->save();

        return back()->with('message', "Info for {$group->name} updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('groups.index')->with('message', "{$group->name} was removed.");
    }

      /**
       * Add a person to the group
       */
      public function addPerson(AddPersonToGroupRequest $request, Group $group)
      {
          $person = Auth::user()->people()->where('id', $request->safe()->personId)->first();

          $role = $request->safe()->role;

          $group->people()->attach($person->id, ['role' => $role]);

          return back()->with('message', "{$person->name} added to {$group->name}");
      }

      /**
       * Remove a person from the group
       */
      public function removePerson(Request $request, Group $group)
      {
          $person = Auth::user()->people()->where('id', $request->personId)->first();

          $group->people()->detach($person->id);

          return redirect()->back()->with('message', "{$person->name} removed from {$group->name}");
      }
}
