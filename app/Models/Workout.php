<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

    protected $fillable = ['date', 'note'];

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)->withTimestamps();
    }

    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
