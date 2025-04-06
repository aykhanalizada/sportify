<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::with('muscleGroups')->get();
        return view('exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $muscleGroups = MuscleGroup::select('id', 'name')->get();
        return view('exercises.create', compact('muscleGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:exercises',
            'movement' => ['required', Rule::in(['bilateral', 'unilateral'])],
            'is_bodyweight' => 'sometimes|boolean',
            'is_timed' => 'sometimes|boolean',
            'uses_band' => 'sometimes|boolean',
            'muscle_groups' => 'required|array|min:1',
            'muscle_groups.*.id' => 'required|exists:muscle_groups,id',
            'muscle_groups.*.level' => 'required|in:primary,secondary,tertiary',
        ]);

        // Enforce that timed exercises must be bodyweight
        if ($validated['is_timed'] ?? false) {
            $validated['is_bodyweight'] = true;
            $validated['uses_band'] = false;
        }

        // Enforce that bodyweight exercises can't use bands
        if ($validated['is_bodyweight'] ?? false) {
            $validated['uses_band'] = false;
        }

        $exercise = Exercise::create([
            'name' => $validated['name'],
            'movement' => $validated['movement'],
            'is_bodyweight' => $validated['is_bodyweight'] ?? false,
            'is_timed' => $validated['is_timed'] ?? false,
            'uses_band' => $validated['uses_band'] ?? false,
        ]);

        // Attach muscle groups with levels
        $muscleGroups = [];
        foreach ($validated['muscle_groups'] as $muscleGroup) {
            $muscleGroups[$muscleGroup['id']] = ['level' => $muscleGroup['level']];
        }
        $exercise->muscleGroups()->sync($muscleGroups);

        return redirect()->route('exercises.index')
            ->with('success', 'Exercise created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $exercise = Exercise::with('muscleGroups')->findOrFail($id);
        $muscleGroups = MuscleGroup::select('id', 'name')->get();
        return view('exercises.edit', compact('exercise', 'muscleGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exercise = Exercise::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:exercises,name,' . $id,
            'movement' => ['required', Rule::in(['bilateral', 'unilateral'])],
            'is_bodyweight' => 'sometimes|boolean',
            'is_timed' => 'sometimes|boolean',
            'uses_band' => 'sometimes|boolean',
            'muscle_groups' => 'required|array|min:1',
            'muscle_groups.*.id' => 'required|exists:muscle_groups,id',
            'muscle_groups.*.level' => 'required|in:primary,secondary,tertiary',
        ]);

        // Enforce that timed exercises must be bodyweight
        if ($validated['is_timed'] ?? false) {
            $validated['is_bodyweight'] = true;
            $validated['uses_band'] = false;
        }

        // Enforce that bodyweight exercises can't use bands
        if ($validated['is_bodyweight'] ?? false) {
            $validated['uses_band'] = false;
        }

        $exercise->update([
            'name' => $validated['name'],
            'movement' => $validated['movement'],
            'is_bodyweight' => $validated['is_bodyweight'] ?? false,
            'is_timed' => $validated['is_timed'] ?? false,
            'uses_band' => $validated['uses_band'] ?? false,
        ]);

        // Prepare muscle groups data for sync
        $muscleGroups = [];
        foreach ($validated['muscle_groups'] as $muscleGroup) {
            $muscleGroups[$muscleGroup['id']] = ['level' => $muscleGroup['level']];
        }

        $exercise->muscleGroups()->sync($muscleGroups);

        return redirect()->route('exercises.index')
            ->with('success', 'Exercise updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();
        return redirect()->route('exercises.index')
            ->with('success', 'Exercise deleted successfully');
    }
}
