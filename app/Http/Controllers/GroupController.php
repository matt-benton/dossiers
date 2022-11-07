<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveGroupRequest;
use App\Models\Group;
use Auth;
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
          'groups' => Auth::user()->groups,
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
      $group->load('people');

      return inertia('Groups/ShowGroup', [
        'group' => $group,
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
        return inertia('Groups/EditGroup', [
          'group' => $group,
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
}