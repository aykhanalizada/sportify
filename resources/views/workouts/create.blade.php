@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Workout</h4>

                        <form class="forms-sample" action="{{ route('workouts.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                                @error('date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <div class="row align-items-center mb-3">
                                    <label for="exercises" class="form-label fw-bold">Exercises</label>

                                    <div class="col-md-10">
                                        <select class="choices-select" id="exercises" name="exercise_ids[]" multiple>
                                            @foreach($exercises as $exercise)
                                                <option value="{{ $exercise->id }}"
                                                        data-is-timed="{{ $exercise->is_timed }}">{{ $exercise->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 text-end">
                                        <button id="increaseSetBtn" type="button" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-plus"></i> Add Set
                                        </button>
                                    </div>
                                </div>

                                @error('exercises')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="exercise-sets-container" class="mt-4">
                                    <div class="exercise-set">
                                        <!-- Set heading -->
                                        <div class="set-header mb-2">
                                            <h5 class="fw-bold">Set 1</h5>
                                        </div>

                                        <!-- Regular exercise inputs (weight/reps) -->
                                        <div class="regular-exercise-inputs">
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label class="form-label">Side</label>
                                                    <select class="form-select side-select" name="side[1]">
                                                        <option value="same">Same</option>
                                                        <option value="different">Different</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Both sides inputs (default) -->
                                            <div class="both-sides-inputs">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Weight (kg)</label>
                                                        <input type="text" class="form-control" name="weight[1]"
                                                               placeholder="e.g. 50">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Reps</label>
                                                        <input type="text" class="form-control" name="reps[1]"
                                                               placeholder="e.g. 10">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Left/Right specific inputs (initially hidden) -->
                                            <div class="side-specific-inputs" style="display: none;">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="card-title mb-3">Left Side</h6>
                                                                <div class="row mb-2">

                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Weight (kg)</label>
                                                                        <input type="text" class="form-control"
                                                                               name="left_weight[1]"
                                                                               placeholder="e.g. 50">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Reps</label>
                                                                        <input type="text" class="form-control"
                                                                               name="left_reps[1]"
                                                                               placeholder="e.g. 10">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6 class="card-title mb-3">Right Side</h6>
                                                                <div class="row mb-2">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Weight (kg)</label>
                                                                        <input type="text" class="form-control"
                                                                               name="right_weight[1]"
                                                                               placeholder="e.g. 50">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Reps</label>
                                                                        <input type="text" class="form-control"
                                                                               name="right_reps[1]"
                                                                               placeholder="e.g. 10">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Timed exercise input -->
                                        <div class="timed-exercise-input" style="display: none;">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Duration (seconds)</label>
                                                    <input type="number" class="form-control" name="seconds[1]"
                                                           placeholder="e.g. 60">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control" id="note" name="note"
                                          placeholder="Enter your notes"></textarea>
                                @error('note')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                                <a href="{{ route('workouts.index') }}" class="btn btn-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let increaseBtn = document.querySelector('#increaseSetBtn');
        let exerciseSetsContainer = document.querySelector('#exercise-sets-container');
        let setCounter = 1;
        let isTimedExercise = false;

        // Function to check if selected exercise is timed
        function checkIfTimedExercise() {
            const exerciseSelect = document.querySelector('#exercises');
            const selectedOptions = Array.from(exerciseSelect.selectedOptions);

            // If any selected exercise is timed, show timed input
            isTimedExercise = selectedOptions.some(option => option.dataset.isTimed === "1");

            // Show/hide appropriate inputs for all sets
            updateInputsVisibility();
        }

        // Function to update visibility of inputs based on exercise type
        function updateInputsVisibility() {
            const regularInputs = document.querySelectorAll('.regular-exercise-inputs');
            const timedInputs = document.querySelectorAll('.timed-exercise-input');

            regularInputs.forEach(input => {
                input.style.display = isTimedExercise ? 'none' : 'block';
            });

            timedInputs.forEach(input => {
                input.style.display = isTimedExercise ? 'block' : 'none';
            });
        }

        // Function to toggle side-specific inputs
        function toggleSideSpecificInputs(select) {
            const setContainer = select.closest('.exercise-set');
            const bothSidesInputs = setContainer.querySelector('.both-sides-inputs');
            const sideSpecificInputs = setContainer.querySelector('.side-specific-inputs');

            if (select.value === 'different') {
                bothSidesInputs.style.display = 'none';
                sideSpecificInputs.style.display = 'block';
            } else {
                bothSidesInputs.style.display = 'block';
                sideSpecificInputs.style.display = 'none';
            }
        }

        // Add event listener to the initial side select
        document.querySelector('.side-select').addEventListener('change', function () {
            toggleSideSpecificInputs(this);
        });

        // Listen for changes in exercise selection
        document.querySelector('#exercises').addEventListener('change', checkIfTimedExercise);

        // Add new set button functionality
        increaseBtn.addEventListener('click', function () {
            setCounter++;

            const newSetDiv = document.createElement('div');
            newSetDiv.className = 'exercise-set mt-4 pt-4 border-top';

            // Create HTML for the new set
            newSetDiv.innerHTML = `
                <!-- Set heading -->
                <div class="set-header mb-2 d-flex justify-content-between">
                    <h5 class="fw-bold">Set ${setCounter}</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-set">
                        <i class="mdi mdi-close"></i> Remove
                    </button>
                </div>

                <!-- Regular exercise inputs -->
                <div class="regular-exercise-inputs" style="display: ${isTimedExercise ? 'none' : 'block'}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Side</label>
                            <select class="form-select side-select" name="side[${setCounter}]">
                                <option value="same">Same</option>
                                <option value="different">Different</option>
                            </select>
                        </div>
                    </div>

                    <!-- Both sides inputs (default) -->
                    <div class="both-sides-inputs">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Reps</label>
                                <input type="text" class="form-control" name="reps[${setCounter}]" placeholder="e.g. 10">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Weight (kg)</label>
                                <input type="text" class="form-control" name="weight[${setCounter}]" placeholder="e.g. 50">
                            </div>
                        </div>
                    </div>

                    <!-- Left/Right specific inputs (initially hidden) -->
                    <div class="side-specific-inputs" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Left Side</h6>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Weight (kg)</label>
                                                <input type="text" class="form-control" name="left_weight[${setCounter}]" placeholder="e.g. 50">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Reps</label>
                                                <input type="text" class="form-control" name="left_reps[${setCounter}]" placeholder="e.g. 10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Right Side</h6>
                                        <div class="row mb-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Weight (kg)</label>
                                                <input type="text" class="form-control" name="right_weight[${setCounter}]" placeholder="e.g. 50">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Reps</label>
                                                <input type="text" class="form-control" name="right_reps[${setCounter}]" placeholder="e.g. 10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timed exercise input -->
                <div class="timed-exercise-input" style="display: ${isTimedExercise ? 'block' : 'none'}">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Duration (seconds)</label>
                            <input type="number" class="form-control" name="seconds[${setCounter}]" placeholder="e.g. 60">
                        </div>
                    </div>
                </div>
            `;

            exerciseSetsContainer.appendChild(newSetDiv);

            // Add event listener to the new side select
            const newSideSelect = newSetDiv.querySelector('.side-select');
            newSideSelect.addEventListener('change', function () {
                toggleSideSpecificInputs(this);
            });

            // Add event listener to the remove button
            const removeBtn = newSetDiv.querySelector('.remove-set');
            removeBtn.addEventListener('click', function () {
                newSetDiv.remove();
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            checkIfTimedExercise();
        });
    </script>
@endpush
