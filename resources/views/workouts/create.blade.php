@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Workout</h4>
                        <form class="forms-sample" action="{{ route('workouts.store') }}" method="POST" id="workoutForm">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
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
                                                        data-movement="{{ $exercise->movement }}"
                                                        data-is-bodyweight="{{ $exercise->is_bodyweight ? 'true' : 'false' }}"
                                                        data-is-timed="{{ $exercise->is_timed ? 'true' : 'false' }}">
                                                    {{ $exercise->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @error('exercise_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div id="exercise-sets-container" class="mt-4">
                                    <!-- Exercise sets will be added here -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                startView: 2
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exerciseSelect = document.getElementById('exercises');
            const exerciseSetsContainer = document.getElementById('exercise-sets-container');
            let exerciseSetCounters = {};

            // Event delegation for remove buttons
            exerciseSetsContainer.addEventListener('click', function(event) {
                const removeBtn = event.target.closest('.remove-set-btn');
                if (removeBtn) {
                    const setElement = removeBtn.closest('.exercise-set');
                    if (setElement) {
                        setElement.remove();
                    }
                }
            });

            exerciseSelect.addEventListener('change', function () {
                const selectedExercises = [...exerciseSelect.selectedOptions].map(opt => opt.value);

                // Remove unselected exercise sections
                document.querySelectorAll('.exercise-section').forEach(section => {
                    if (!selectedExercises.includes(section.dataset.exerciseId)) {
                        section.remove();
                    }
                });

                // Add new exercise sections
                selectedExercises.forEach(exerciseId => {
                    if (!document.querySelector(`#exercise-${exerciseId}-section`)) {
                        addExerciseSection(exerciseId);
                    }
                });
            });

            function addExerciseSection(exerciseId) {
                const selectedOption = exerciseSelect.querySelector(`[value="${exerciseId}"]`);
                const exerciseName = selectedOption.textContent;
                const movement = selectedOption.dataset.movement;
                const isBodyweight = selectedOption.dataset.isBodyweight === 'true';
                const isTimed = selectedOption.dataset.isTimed === 'true';

                const exerciseWrapper = document.createElement('div');
                exerciseWrapper.classList.add('exercise-section', 'mb-4');
                exerciseWrapper.id = `exercise-${exerciseId}-section`;
                exerciseWrapper.dataset.exerciseId = exerciseId;
                exerciseWrapper.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold">${exerciseName}</h5>
                <button type="button" class="btn btn-sm btn-success add-set-btn"
                        data-exercise-id="${exerciseId}"
                        data-movement="${movement}"
                        data-is-bodyweight="${isBodyweight}"
                        data-is-timed="${isTimed}">
                    <i class="mdi mdi-plus"></i> Add Set
                </button>
            </div>
            <div id="exercise-${exerciseId}-sets" class="mt-2"></div>
        `;

                exerciseSetsContainer.appendChild(exerciseWrapper);
                exerciseSetCounters[exerciseId] = 0;

                exerciseWrapper.querySelector('.add-set-btn').addEventListener('click', function() {
                    addNewSet(
                        exerciseId,
                        this.dataset.movement,
                        this.dataset.isBodyweight === 'true',
                        this.dataset.isTimed === 'true'
                    );
                });
            }

            function addNewSet(exerciseId, movement, isBodyweight, isTimed) {
                exerciseSetCounters[exerciseId] = (exerciseSetCounters[exerciseId] || 0) + 1;
                const setNumber = exerciseSetCounters[exerciseId];

                const setContainer = document.getElementById(`exercise-${exerciseId}-sets`);
                const setElement = document.createElement('div');
                setElement.classList.add('exercise-set', 'mb-3', 'p-3', 'border', 'rounded', 'bg-light');
                setElement.dataset.setNumber = setNumber;

                if (isTimed) {
                    setElement.innerHTML = `
                      <div class="row align-items-center">
                            <div class="col-md-2 fw-bold">Set ${setNumber}</div>
                            <div class="col-md-3">
                                <label>Seconds</label>
                                <input type="number" name="sets[${exerciseId}][${setNumber}][duration_seconds]"
                                    class="form-control" min="1" placeholder="seconds" required>
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-set-btn mt-4">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                     </div>
        `;
                } else if (movement === 'unilateral') {
                    setElement.innerHTML = `
            <div class="d-flex align-items-start gap-3">
                <div class="form-group col-md-1 m-auto">
                    <span class="fw-bold">Set ${setNumber}</span>
                </div>
                <div class="flex-grow-1">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">L:</span>
                                <input type="number" name="sets[${exerciseId}][${setNumber}][left_reps]"
                                       class="form-control form-control-sm" placeholder="Reps" min="1" required>
                                ${!isBodyweight ? `
                                <input type="number" step="0.1" name="sets[${exerciseId}][${setNumber}][left_weight]"
                                       class="form-control form-control-sm" placeholder="kg" min="0">
                                ` : ''}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">R:</span>
                                <input type="number" name="sets[${exerciseId}][${setNumber}][right_reps]"
                                       class="form-control form-control-sm" placeholder="Reps" min="1" required>
                                ${!isBodyweight ? `
                                <input type="number" step="0.1" name="sets[${exerciseId}][${setNumber}][right_weight]"
                                       class="form-control form-control-sm" placeholder="kg" min="0">
                                ` : ''}
                                <button type="button" class="btn btn-sm btn-danger remove-set-btn ms-auto">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
                } else {
                    setElement.innerHTML = `
                      <div class="row align-items-center">
                            <div class="col-md-2 fw-bold">Set ${setNumber}</div>
                            <div class="col-md-3">
                                <label>Reps</label>
                                <input type="number" name="sets[${exerciseId}][${setNumber}][reps]" class="form-control" min="1" required="">
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-danger remove-set-btn mt-4">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                     </div>
        `;
                }

                setContainer.appendChild(setElement);
            }
        });
    </script>
@endpush
