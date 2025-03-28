<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Workout frequency data (last 8 weeks)
        $workoutFrequency = $this->getWorkoutFrequencyData();

        // Exercise type distribution data
        $exerciseTypeDistribution = $this->getExerciseTypeDistributionData();

        // Volume over time data (last 6 workouts)
        $volumeOverTime = $this->getVolumeOverTimeData();

        // Top exercises data
        $topExercises = $this->getTopExercisesData();

        // Recent workouts with calculated volume
        $recentWorkouts = Workout::withCount(['sets'])
            ->with(['sets.exercise'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get()
            ->each(function ($workout) {
                $workout->total_volume = $this->calculateWorkoutVolume($workout);
                $workout->has_bodyweight = $this->hasBodyweightExercises($workout);
                $workout->date = Carbon::parse($workout->date);
            });

        return view('dashboard', compact(
            'workoutFrequency',
            'exerciseTypeDistribution',
            'volumeOverTime',
            'topExercises',
            'recentWorkouts'
        ));
    }

    protected function hasBodyweightExercises($workout)
    {
        return $workout->sets->contains(function($set) {
            return $set->exercise->is_bodyweight;
        });
    }

    protected function calculateWorkoutVolume($workout)
    {
        $totalVolume = 0;
        $hasBodyweight = false;

        foreach ($workout->sets as $set) {
            $exercise = $set->exercise;

            if ($exercise->is_timed) {
                continue; // Skip timed exercises for volume calculation
            }

            if ($exercise->is_bodyweight) {
                $hasBodyweight = true;
                if ($exercise->movement === 'unilateral') {
                    $totalVolume += ($set->left_reps ?? 0) + ($set->right_reps ?? 0);
                } else {
                    $totalVolume += $set->reps ?? 0;
                }
            } else {
                if ($exercise->movement === 'unilateral') {
                    $totalVolume += (($set->left_weight ?? 0) * ($set->left_reps ?? 0)) +
                        (($set->right_weight ?? 0) * ($set->right_reps ?? 0));
                } else {
                    $totalVolume += ($set->weight ?? 0) * ($set->reps ?? 0);
                }
            }
        }

        return $totalVolume;
    }

    protected function getWorkoutFrequencyData()
    {
        $startDate = Carbon::now()->subWeeks(8)->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $workoutsByWeek = Workout::select(
            DB::raw('WEEK(date, 1) as week'),
            DB::raw('COUNT(*) as count')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        // Generate week range labels
        $labels = [];
        $data = [];
        $currentWeek = $startDate->copy();

        while ($currentWeek <= $endDate) {
            $weekNumber = $currentWeek->weekOfYear;
            $weekStart = $currentWeek->copy()->startOfWeek();
            $weekEnd = $currentWeek->copy()->endOfWeek();

            if ($weekStart->month !== $weekEnd->month) {
                $label = $weekStart->format('M j') . '-' . $weekEnd->format('M j');
            } else {
                $label = $weekStart->format('M j') . '-' . $weekEnd->format('j');
            }

            $count = $workoutsByWeek->firstWhere('week', $weekNumber)->count ?? 0;

            $labels[] = $label;
            $data[] = $count;

            $currentWeek->addWeek();
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    protected function getExerciseTypeDistributionData()
    {
        return [
            'bilateral' => Exercise::where('movement', 'bilateral')->count(),
            'unilateral' => Exercise::where('movement', 'unilateral')->count(),
            'timed' => Exercise::where('is_timed', true)->count(),
            'bodyweight' => Exercise::where('is_bodyweight', true)->count()
        ];
    }

    protected function getVolumeOverTimeData()
    {
        $workouts = Workout::with(['sets.exercise'])
            ->orderBy('date', 'desc')
            ->take(6)
            ->get()
            ->map(function ($workout) {
                $weightedVolume = 0;
                $bodyweightVolume = 0;

                foreach ($workout->sets as $set) {
                    $exercise = $set->exercise;

                    if ($exercise->is_bodyweight) {
                        if ($exercise->movement === 'unilateral') {
                            $bodyweightVolume += ($set->left_reps ?? 0) + ($set->right_reps ?? 0);
                        } else {
                            $bodyweightVolume += $set->reps ?? 0;
                        }
                    } else {
                        if ($exercise->movement === 'unilateral') {
                            $weightedVolume += (($set->left_weight ?? 0) * ($set->left_reps ?? 0)) +
                                (($set->right_weight ?? 0) * ($set->right_reps ?? 0));
                        } else {
                            $weightedVolume += ($set->weight ?? 0) * ($set->reps ?? 0);
                        }
                    }
                }

                return [
                    'date' => Carbon::parse($workout->date)->format('M d'),
                    'weighted_volume' => $weightedVolume,
                    'bodyweight_volume' => $bodyweightVolume
                ];
            })
            ->reverse();

        return [
            'labels' => $workouts->pluck('date'),
            'weighted_data' => $workouts->pluck('weighted_volume'),
            'bodyweight_data' => $workouts->pluck('bodyweight_volume')
        ];
    }

    protected function getTopExercisesData()
    {
        $data = DB::table('workout_sets')
            ->join('exercises', 'workout_sets.exercise_id', '=', 'exercises.id')
            ->select(
                'exercises.id',
                'exercises.name as exercise',
                DB::raw('COUNT(workout_sets.id) as total_sets')
            )
            ->groupBy('exercises.id', 'exercises.name')
            ->orderBy('total_sets', 'desc')
            ->limit(5)
            ->get();

        return [
            'labels' => $data->pluck('exercise')->toArray(),
            'data' => $data->pluck('total_sets')->toArray()
        ];
    }
}
