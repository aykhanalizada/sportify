<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['name'];

    public function muscleGroups()
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_group',
            'exercise_id', 'muscle_group_id')
            ->withTimestamps();
    }
}
