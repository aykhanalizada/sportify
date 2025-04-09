@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Edit Workout</h4>

                        <form class="forms-sample" action="{{ route('workouts.update', $workout->id) }}" method="POST"
                              id="workoutForm">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" class="form-control" id="date" name="date"
                                       value="{{ old('date', $workout->date->format('Y-m-d')) }}" required>
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
                                                        data-is-timed="{{ $exercise->is_timed ? 'true' : 'false' }}"
                                                        data-uses-band="{{ $exercise->uses_band ? 'true' : 'false' }}"
                                                    {{ in_array($exercise->id, $workout->sets->pluck('exercise_id')->unique()->toArray()) ? 'selected' : '' }}>
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
                                    @foreach($workout->sets->groupBy('exercise_id') as $exerciseId => $sets)
                                        @php
                                            $exercise = $exercises->firstWhere('id', $exerciseId);
                                        @endphp
                                        <div class="exercise-section mb-4" id="exercise-{{ $exerciseId }}-section"
                                             data-exercise-id="{{ $exerciseId }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="fw-bold">{{ $exercise->name }}</h5>
                                                <button type="button" class="btn btn-sm btn-success add-set-btn"
                                                        data-exercise-id="{{ $exerciseId }}"
                                                        data-movement="{{ $exercise->movement }}"
                                                        data-is-bodyweight="{{ $exercise->is_bodyweight ? 'true' : 'false' }}"
                                                        data-is-timed="{{ $exercise->is_timed ? 'true' : 'false' }}"
                                                        data-uses-band="{{ $exercise->uses_band ? 'true' : 'false' }}">
                                                    <i class="mdi mdi-plus"></i> Add Set
                                                </button>
                                            </div>
                                            <div id="exercise-{{ $exerciseId }}-sets" class="mt-2">
                                                @foreach($sets as $set)
                                                    <div class="exercise-set mb-3 p-3 border rounded bg-light"
                                                         data-set-id="{{ $set->id }}">
                                                        @if($exercise->is_timed)
                                                            <!-- Timed exercise display (unchanged) -->
                                                        @elseif($exercise->movement === 'unilateral')
                                                            <div class="d-flex align-items-start gap-3">
                                                                <div class="form-group col-md-1 m-auto">
                                                                    <span class="fw-bold">Set {{ $loop->iteration }}</span>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="row g-2">
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <span class="small text-muted">L:</span>
                                                                                @if(!$exercise->is_bodyweight && !$exercise->uses_band)
                                                                                    <input type="number" step="0.1"
                                                                                           name="sets[{{ $exerciseId }}][{{ $set->id }}][left_weight]"
                                                                                           class="form-control form-control-sm"
                                                                                           placeholder="kg"
                                                                                           value="{{ $set->left_weight }}"
                                                                                           min="0">
                                                                                @endif
                                                                                <input type="number"
                                                                                       name="sets[{{ $exerciseId }}][{{ $set->id }}][left_reps]"
                                                                                       class="form-control form-control-sm"
                                                                                       placeholder="Reps"
                                                                                       value="{{ $set->left_reps }}"
                                                                                       min="1" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <span class="small text-muted">R:</span>
                                                                                @if(!$exercise->is_bodyweight && !$exercise->uses_band)
                                                                                    <input type="number" step="0.1"
                                                                                           name="sets[{{ $exerciseId }}][{{ $set->id }}][right_weight]"
                                                                                           class="form-control form-control-sm"
                                                                                           placeholder="kg"
                                                                                           value="{{ $set->right_weight }}"
                                                                                           min="0">
                                                                                @endif
                                                                                <input type="number"
                                                                                       name="sets[{{ $exerciseId }}][{{ $set->id }}][right_reps]"
                                                                                       class="form-control form-control-sm"
                                                                                       placeholder="Reps"
                                                                                       value="{{ $set->right_reps }}"
                                                                                       min="1" required>
                                                                                <button type="button"
                                                                                        class="btn btn-sm btn-danger remove-set-btn ms-auto">
                                                                                    <i class="mdi mdi-minus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden"
                                                                       name="sets[{{ $exerciseId }}][{{ $set->id }}][id]"
                                                                       value="{{ $set->id }}">
                                                            </div>
                                                        @else
                                                            <!-- Bilateral exercise -->
                                                            <div class="row align-items-center">
                                                                <div class="col-md-2 fw-bold">Set {{ $loop->iteration }}</div>
                                                                @if(!$exercise->is_bodyweight && !$exercise->uses_band)
                                                                    <div class="col-md-3">
                                                                        <label>Weight (kg)</label>
                                                                        <input type="number" step="0.1"
                                                                               name="sets[{{ $exerciseId }}][{{ $set->id }}][weight]"
                                                                               class="form-control" min="0"
                                                                               value="{{ $set->weight }}">
                                                                    </div>
                                                                @endif
                                                                <div class="col-md-3">
                                                                    <label>Reps</label>
                                                                    <input type="number"
                                                                           name="sets[{{ $exerciseId }}][{{ $set->id }}][reps]"
                                                                           class="form-control" min="1"
                                                                           value="{{ $set->reps }}" required>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-danger remove-set-btn mt-4">
                                                                        <i class="mdi mdi-minus"></i>
                                                                    </button>
                                                                </div>
                                                                <input type="hidden"
                                                                       name="sets[{{ $exerciseId }}][{{ $set->id }}][id]"
                                                                       value="{{ $set->id }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea class="form-control" id="note" name="note"
                                          placeholder="Enter your notes">{{ old('note', $workout->note) }}</textarea>
                                @error('note')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-gradient-primary me-2">Update</button>
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
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                startView: 2
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const exerciseSelect = document.getElementById('exercises');
            const exerciseSetsContainer = document.getElementById('exercise-sets-container');
            let exerciseSetCounters = {};

            // Initialize counters based on existing sets
            document.querySelectorAll('.exercise-section').forEach(section => {
                const exerciseId = section.dataset.exerciseId;
                const setCount = section.querySelectorAll('.exercise-set').length;
                exerciseSetCounters[exerciseId] = setCount;

                // Add click handler for existing add-set buttons
                const addBtn = section.querySelector('.add-set-btn');
                if (addBtn) {
                    addBtn.addEventListener('click', function() {
                        addNewSet(
                            exerciseId,
                            this.dataset.movement,
                            this.dataset.isBodyweight === 'true',
                            this.dataset.isTimed === 'true',
                            this.dataset.usesBand === 'true'
                        );
                    });
                }
            });

            // Event delegation for remove buttons
            exerciseSetsContainer.addEventListener('click', function (event) {
                const removeBtn = event.target.closest('.remove-set-btn');
                if (removeBtn) {
                    const setElement = removeBtn.closest('.exercise-set');
                    if (setElement) {
                        if (setElement.dataset.setId) {
                            const deleteInput = document.createElement('input');
                            deleteInput.type = 'hidden';
                            deleteInput.name = 'delete_sets[]';
                            deleteInput.value = setElement.dataset.setId;
                            setElement.parentNode.appendChild(deleteInput);
                        }
                        setElement.remove();

                        // Update set numbers for remaining sets
                        const exerciseSection = setElement.closest('.exercise-section');
                        if (exerciseSection) {
                            const exerciseId = exerciseSection.dataset.exerciseId;
                            updateSetNumbers(exerciseId);
                        }
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
                const usesBand = selectedOption.dataset.usesBand === 'true';

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
                        data-is-timed="${isTimed}"
                        data-uses-band="${usesBand}">
                    <i class="mdi mdi-plus"></i> Add Set
                </button>
            </div>
            <div id="exercise-${exerciseId}-sets" class="mt-2"></div>
        `;

                exerciseSetsContainer.appendChild(exerciseWrapper);
                exerciseSetCounters[exerciseId] = 0;

                exerciseWrapper.querySelector('.add-set-btn').addEventListener('click', function () {
                    addNewSet(
                        exerciseId,
                        this.dataset.movement,
                        this.dataset.isBodyweight === 'true',
                        this.dataset.isTimed === 'true',
                        this.dataset.usesBand === 'true'
                    );
                });
            }

            function addNewSet(exerciseId, movement, isBodyweight, isTimed, usesBand) {
                const setContainer = document.getElementById(`exercise-${exerciseId}-sets`);
                const existingSets = setContainer.querySelectorAll('.exercise-set');
                const setNumber = existingSets.length + 1;
                const newSetId = 'new-' + exerciseId + '-' + Date.now();

                const setElement = document.createElement('div');
                setElement.classList.add('exercise-set', 'mb-3', 'p-3', 'border', 'rounded', 'bg-light');
                setElement.dataset.setNumber = setNumber;

                if (isTimed) {
                    setElement.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-2 fw-bold">Set ${setNumber}</div>
                <div class="col-md-3">
                    <label>Seconds</label>
                    <input type="number" name="sets[${exerciseId}][${newSetId}][duration_seconds]"
                           class="form-control" min="1" placeholder="seconds">
                    <input type="hidden" name="sets[${exerciseId}][${newSetId}][id]" value="${newSetId}">
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
                                ${!isBodyweight && !usesBand ? `
                                <input type="number" step="0.1" name="sets[${exerciseId}][${newSetId}][left_weight]"
                                       class="form-control form-control-sm" placeholder="kg" min="0">
                                ` : ''}
                                <input type="number" name="sets[${exerciseId}][${newSetId}][left_reps]"
                                       class="form-control form-control-sm" placeholder="Reps" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted">R:</span>
                                ${!isBodyweight && !usesBand ? `
                                <input type="number" step="0.1" name="sets[${exerciseId}][${newSetId}][right_weight]"
                                       class="form-control form-control-sm" placeholder="kg" min="0">
                                ` : ''}
                                <input type="number" name="sets[${exerciseId}][${newSetId}][right_reps]"
                                       class="form-control form-control-sm" placeholder="Reps" min="1">
                                <button type="button" class="btn btn-sm btn-danger remove-set-btn ms-auto">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="sets[${exerciseId}][${newSetId}][id]" value="${newSetId}">
            </div>
        `;
                } else {
                    // Bilateral exercise
                    setElement.innerHTML = `
            <div class="row align-items-center">
                <div class="col-md-2 fw-bold">Set ${setNumber}</div>
                ${!isBodyweight && !usesBand ? `
                <div class="col-md-3">
                    <label>Weight (kg)</label>
                    <input type="number" step="0.1" name="sets[${exerciseId}][${newSetId}][weight]"
                           class="form-control" min="0" placeholder="kg">
                </div>
                ` : ''}
                <div class="col-md-3">
                    <label>Reps</label>
                    <input type="number" name="sets[${exerciseId}][${newSetId}][reps]"
                           class="form-control" min="1" placeholder="Reps">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger remove-set-btn mt-4">
                        <i class="mdi mdi-minus"></i>
                    </button>
                </div>
                <input type="hidden" name="sets[${exerciseId}][${newSetId}][id]" value="${newSetId}">
            </div>
        `;
                }

                setContainer.appendChild(setElement);
            }

            function updateSetNumbers(exerciseId) {
                const setContainer = document.getElementById(`exercise-${exerciseId}-sets`);
                if (!setContainer) return;

                const sets = setContainer.querySelectorAll('.exercise-set');
                sets.forEach((set, index) => {
                    const setNumberElement = set.querySelector('.fw-bold');
                    if (setNumberElement) {
                        setNumberElement.textContent = `Set ${index + 1}`;
                    }
                });
                exerciseSetCounters[exerciseId] = sets.length;
            }
        });
    </script>
@endpush
