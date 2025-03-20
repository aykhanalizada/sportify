<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MuscleGroup extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];
}
