<?php

namespace App\View\Components;

use Illuminate\View\Component;

class WorkoutSet extends Component
{

    public $setNumber;

    public $exercise;

    public function __construct($setNumber, $exercise = null)
    {
        $this->setNumber = $setNumber;
        $this->exercise = $exercise;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.workout-set');
    }
}
