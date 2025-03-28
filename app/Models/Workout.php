<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    protected $fillable = ['date', 'note'];

    protected $casts = [
        'date' => 'date', // This will automatically convert to Carbon instance
    ];

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withTimestamps();
    }

    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    public function sets()
    {
        return $this->hasMany(WorkoutSet::class);
    }
}
