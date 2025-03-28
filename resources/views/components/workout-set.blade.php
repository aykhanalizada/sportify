<div class="exercise-set mb-4" data-set-number="{{ $setNumber }}">
    <!-- Set header with remove button -->
    <div class="set-header d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
        <h5 class="fw-bold m-0">Set {{ $setNumber }}</h5>
        @if($setNumber > 1)
            <button type="button" class="btn btn-sm btn-outline-danger remove-set-btn">
                <i class="mdi mdi-close"></i> Remove
            </button>
        @endif
    </div>

    <!-- Input to store the exercise ID -->
    <input type="hidden" name="sets[{{ $setNumber }}][exercise_id]" value="{{ $exercise->id ?? '' }}"
           class="exercise-id-input">

    <!-- Regular exercise inputs (weight/reps) - shown by default unless exercise is timed -->
    <div class="regular-exercise-inputs" style="{{ isset($exercise) && $exercise->is_timed ? 'display: none;' : '' }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Side</label>
                <select class="form-select side-select"
                        name="sets[{{ $setNumber }}][side]" {{ isset($exercise) ? 'disabled' : '' }}>
                    <option value="same" selected>Same</option>
                    <option value="different" {{ isset($exercise) && $exercise->is_bilateral ? 'selected' : '' }}>
                        Different
                    </option>
                </select>
            </div>
        </div>

        <!-- Same side inputs (default) -->
        <div class="both-sides-inputs"
             style="{{ isset($exercise) && !$exercise->is_bilateral ? '' : 'display: none;' }}">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Weight (kg)</label>
                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][weight]" placeholder="e.g. 50">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Reps</label>
                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][reps]" placeholder="e.g. 10">
                </div>

            </div>
        </div>

        <!-- Left/Right specific inputs (shown for bilateral exercises) -->
        <div class="side-specific-inputs"
             style="{{ isset($exercise) && $exercise->is_bilateral ? '' : 'display: none;' }}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Left Side</h6>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Weight (kg)</label>
                                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][left_weight]"
                                           placeholder="e.g. 50">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Reps</label>
                                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][left_reps]"
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
                                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][right_weight]"
                                           placeholder="e.g. 50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reps</label>
                                    <input type="text" class="form-control" name="sets[{{ $setNumber }}][right_reps]"
                                           placeholder="e.g. 10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timed exercise input - shown only for timed exercises -->
    <div class="timed-exercise-input" style="{{ isset($exercise) && $exercise->is_timed ? '' : 'display: none;' }}">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Duration (seconds)</label>
                <input type="number" class="form-control" name="sets[{{ $setNumber }}][seconds]" placeholder="e.g. 60">
            </div>
        </div>
    </div>
</div>
