<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WorkoutSetEdit extends Component
{

    public $setNumber;

    public $exercise;
    public $workoutSet;
    public $workout;

    public function __construct($setNumber, $workout, $exercise = null, $workoutSet = null)
    {
        $this->setNumber = $setNumber;
        $this->exercise = $exercise;
        $this->workoutSet = $workoutSet;
        $this->workout = $workout;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.workout-set-edit');
    }
}
