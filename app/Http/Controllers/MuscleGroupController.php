<?php

namespace App\Http\Controllers;

use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $muscleGroups = MuscleGroup::select('id', 'name')->get();

        return view('muscle-group.index', compact('muscleGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('muscle-group.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MuscleGroup::create([
            'name' => $request->name
        ]);

        return redirect()->route('muscle-groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $muscleGroup = MuscleGroup::findOrFail($id);

        return view('muscle-group.edit', compact('muscleGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $muscleGroup = MuscleGroup::findOrFail($id);

        $muscleGroup->name = $request->name;
        $muscleGroup->save();

        return redirect()->route('muscle-groups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $muscleGroup = MuscleGroup::findOrFail($id);

        $muscleGroup->delete();

        return redirect()->route('muscle-groups.index');
    }
}
