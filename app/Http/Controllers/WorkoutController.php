<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workouts = Workout::all();


        return view('workouts.index', compact('workouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exercises = Exercise::all();

        return view('workouts.create', compact('exercises'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

        $workout = Workout::create([
            'date' => $request->date,
            'note' => $request->note
        ]);

        $exerciseData = [];

        foreach ($request->exercise_ids as $exerciseId) {
            $exerciseData[$exerciseId] = [
                'best_reps' => $request->best_reps[$exerciseId] ?? null,
                'best_weight_kg' => $request->best_weight[$exerciseId] ?? null,
            ];

            foreach ($request->sets[$exerciseId] as $setNumber => $setData) {
                DB::table('workout_sets')->insert([
                    'workout_id' => $workout->id,
                    'exercise_id' => $exerciseId,
                    'set_number' => $setNumber + 1,
                    'reps' => $setData['reps'] ?? null,
                    'weight_kg' => $setData['weight_kg'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

        }

        $workout->exercises()->attach($exerciseData);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
