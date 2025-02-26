<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::with('muscleGroups')
            ->where('is_deleted', 0)
            ->get();

        return view('exercises.index', compact('exercises',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $muscleGroups = MuscleGroup::select('id', 'name')
            ->where('is_deleted', 0)
            ->get();

        return view('exercises.create', compact('muscleGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exercise = Exercise::create([
            'name' => $request->name
        ]);

        $exercise->muscleGroups()->attach($request->muscle_groups);

        return redirect()->route('exercises.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exercise = Exercise::with('muscleGroups')
            ->findOrFail($id);

        $muscleGroups = MuscleGroup::select('id', 'name')
            ->where('is_deleted', 0)
            ->get();

        return view('exercises.edit', compact('exercise', 'muscleGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exercise = Exercise::findOrFail($id);

        $exercise->name = $request->name;

        $exercise->muscleGroups()->sync($request->muscle_groups);

        return redirect()->route('exercises.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exercise = Exercise::findOrFail($id);

        $exercise->is_deleted = 1;
        $exercise->save();

        return redirect()->route('exercises.index');
    }
}
