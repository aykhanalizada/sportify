<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','movement','is_bodyweight','is_timed','uses_band'];

    public function muscleGroups()
    {
        return $this->belongsToMany(MuscleGroup::class)
            ->withPivot('level')
            ->withTimestamps();
    }
}
