<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSet extends Model
{
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'set_number',
        'reps',
        'weight',
        'left_reps',
        'left_weight',
        'right_reps',
        'right_weight',
        'duration_seconds',
        'is_drop_set'
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
