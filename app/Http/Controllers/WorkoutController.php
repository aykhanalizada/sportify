<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::with(['sets.exercise'])
            ->orderBy('date', 'desc')
            ->get();



        return view('workouts.index', compact('workouts'));
    }

    public function create()
    {
        $exercises = Exercise::orderBy('name')->get();
        return view('workouts.create', compact('exercises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:workouts,date',
            'note' => 'nullable|string',
            'sets' => 'required|array',
            'sets.*.*.reps' => 'nullable|integer|min:1',
            'sets.*.*.weight' => 'nullable|numeric|min:0',
            'sets.*.*.left_reps' => 'nullable|integer|min:1',
            'sets.*.*.left_weight' => 'nullable|numeric|min:0',
            'sets.*.*.right_reps' => 'nullable|integer|min:1',
            'sets.*.*.right_weight' => 'nullable|numeric|min:0',
            'sets.*.*.duration_seconds' => 'nullable|integer|min:1'
        ]);

        DB::transaction(function () use ($validated) {
            $workout = Workout::create([
                'date' => $validated['date'],
                'note' => $validated['note'] ?? null
            ]);

            foreach ($validated['sets'] as $exerciseId => $sets) {
                foreach ($sets as $setNumber => $setData) {
                    $this->createWorkoutSet($workout, $exerciseId, $setNumber, $setData);
                }
            }
        });

        return redirect()->route('workouts.index')
            ->with('success', 'Workout created successfully');
    }

    public function show($id)
    {
        $workout = Workout::with(['sets.exercise'])
            ->findOrFail($id);

        $groupedSets = $workout->sets->groupBy('exercise_id');

        return view('workouts.show', [
            'workout' => $workout,
            'groupedSets' => $groupedSets
        ]);
    }

    public function edit($id)
    {
        $workout = Workout::with(['sets.exercise'])
            ->findOrFail($id);

        $exercises = Exercise::orderBy('name')->get();
        $groupedSets = $workout->sets->groupBy('exercise_id');

        return view('workouts.edit', compact('workout', 'exercises', 'groupedSets'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'note' => 'nullable|string',
            'sets' => 'required|array',
            'sets.*.*.id' => 'required|string',
            'sets.*.*.reps' => 'nullable|integer|min:1',
            'sets.*.*.weight' => 'nullable|numeric|min:0',
            'sets.*.*.left_reps' => 'nullable|integer|min:1',
            'sets.*.*.left_weight' => 'nullable|numeric|min:0',
            'sets.*.*.right_reps' => 'nullable|integer|min:1',
            'sets.*.*.right_weight' => 'nullable|numeric|min:0',
            'sets.*.*.duration_seconds' => 'nullable|integer|min:1',
            'delete_sets' => 'sometimes|array'
        ]);


        DB::transaction(function () use ($id, $validated) {
            $workout = Workout::findOrFail($id);
            $workout->update([
                'date' => $validated['date'],
                'note' => $validated['note'] ?? null
            ]);

            // Handle deleted sets
            if (!empty($validated['delete_sets'])) {
                WorkoutSet::whereIn('id', $validated['delete_sets'])->delete();
            }

            // Process each exercise's sets
            foreach ($validated['sets'] as $exerciseId => $sets) {
                $setNumber = 1; // Initialize set counter for each exercise

                foreach ($sets as $setKey => $setData) {
                    if (isset($setData['id'])) {
                        if (str_starts_with($setData['id'], 'new-')) {
                            // Create new set (ID starts with 'new-')
                            $this->createWorkoutSet($workout, $exerciseId, $setNumber, $setData);
                        } else {
                            // Update existing set
                            $this->updateWorkoutSet($setData['id'], $setData);
                        }
                        $setNumber++;
                    }
                }
            }
        });

        return redirect()->route('workouts.index')
            ->with('success', 'Workout updated successfully');
    }

    public function destroy($id)
    {
        $workout = Workout::findOrFail($id);
        $workout->delete();

        return redirect()->route('workouts.index')
            ->with('success', 'Workout deleted successfully');
    }

    protected function createWorkoutSet($workout, $exerciseId, $setNumber, $setData)
    {
        $exercise = Exercise::findOrFail($exerciseId);

        $setData = $this->formatSetData($exercise, $setData);

        return $workout->sets()->create(array_merge(
            [
                'exercise_id' => $exerciseId,
                'set_number' => $setNumber
            ],
            $setData
        ));
    }

    protected function updateWorkoutSet($setId, $setData)
    {
        $set = WorkoutSet::findOrFail($setId);
        $exercise = $set->exercise;

        $updateData = $this->formatSetData($exercise, $setData);

        // Nullify unused fields
        $fieldsToNull = [
            'reps', 'weight', 'left_reps', 'left_weight',
            'right_reps', 'right_weight', 'duration_seconds'
        ];

        foreach ($fieldsToNull as $field) {
            if (!array_key_exists($field, $updateData)) {
                $updateData[$field] = null;
            }
        }

        $set->update($updateData);
        return $set;
    }

    protected function formatSetData($exercise, $setData)
    {
        if ($exercise->is_timed) {
            return [
                'duration_seconds' => $setData['duration_seconds'] ?? null
            ];
        }

        if ($exercise->movement === 'unilateral') {
            return [
                'left_reps' => $setData['left_reps'] ?? null,
                'left_weight' => !$exercise->is_bodyweight ? ($setData['left_weight'] ?? null) : null,
                'right_reps' => $setData['right_reps'] ?? null,
                'right_weight' => !$exercise->is_bodyweight ? ($setData['right_weight'] ?? null) : null
            ];
        }

        // Bilateral
        return [
            'reps' => $setData['reps'] ?? null,
            'weight' => !$exercise->is_bodyweight ? ($setData['weight'] ?? null) : null
        ];
    }
}
