<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use  App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::orderBy('id', 'desc')->get();
        return view('roles.index', compact('roles'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
        ]);
        $role = new Role([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'description' => $request->get('description'),
        ]);
        $role->save();
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $role = Role::find($id);
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $role = Role::find($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->get('name');
        $role->slug = $request->get('slug');
        $role->description = $request->get('description');
        $role->save();
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
