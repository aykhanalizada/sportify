<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','is_unilateral'];

    public function muscleGroups()
    {
        return $this->belongsToMany(MuscleGroup::class, 'exercise_muscle_group',
            'exercise_id', 'muscle_group_id')
            ->withTimestamps();
    }
}
