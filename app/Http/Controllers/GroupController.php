<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupMemberRequest;
use App\Http\Requests\SaveGroupRequest;
use App\Models\Group;
use App\Models\Person;
use Auth;
use DB;
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
            'members' => function ($query) {
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
        $memberThreads = $group->members()->with(['threads' => function ($query) {
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

        $combinedThreads = $groupThreads->concat($memberThreads)->sortByDesc('last_development_at')->unique('id');
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
        $group->load(['members' => function ($query) {
            $query->orderBy('name');
        }]);

        $groupedPeopleIds = $group->members->pluck('id');

        $nonMembers = Auth::user()->people()->whereNotIn('id', $groupedPeopleIds)->orderBy('name')->get();

        return inertia('Groups/EditGroup', [
            'group' => $group,
            'nonMembers' => $nonMembers,
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
       * Add a member to the group
       */
      public function addMember(GroupMemberRequest $request, Group $group)
      {
          $member = Auth::user()->people()->where('id', $request->safe()->personId)->first();

          $role = $request->safe()->role;

          $group->members()->attach($member->id, ['role' => $role]);

          return back()->with('message', "{$member->name} added to {$group->name}");
      }

      /**
       * Remove a member from the group
       */
      public function removeMember(GroupMemberRequest $request, Group $group)
      {
          $member = Auth::user()->people()->where('id', $request->personId)->first();

          $group->members()->detach($member->id);

          return redirect()->back()->with('message', "{$member->name} removed from {$group->name}");
      }

      /**
       * Change member role
       */
      public function updateRole(GroupMemberRequest $request, Group $group)
      {
          $role = $request->safe()->role;
          $member = Person::findOrFail($request->safe()->personId);

          $group->members()->updateExistingPivot($member->id, ['role' => $role]);

          return redirect()->back()->with('message', "{$member->name}'s role changed to {$role}");
      }
}
