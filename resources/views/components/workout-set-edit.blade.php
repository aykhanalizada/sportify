<div class="exercise-set mb-3 p-3 border rounded" data-set-id="{{ $set->id ?? '' }}" data-exercise-id="{{ $exercise->id }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">Set #{{ $setNumber }}</h6>
        <button type="button" class="btn btn-sm btn-danger remove-set-btn">
            <i class="mdi mdi-delete"></i> Remove
        </button>
    </div>

    <input type="hidden" name="sets[{{ $exercise->id }}][{{ $setNumber }}][exercise_id]" value="{{ $exercise->id }}">
    @if(isset($set->id))
        <input type="hidden" name="sets[{{ $exercise->id }}][{{ $setNumber }}][id]" value="{{ $set->id }}">
    @endif

    @if($exercise->is_bilateral)
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Left Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][left_weight]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.left_weight", $set->left_weight) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Left Reps</label>
                    <input type="number" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][left_reps]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.left_reps", $set->left_reps) }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Right Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][right_weight]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.right_weight", $set->right_weight) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Right Reps</label>
                    <input type="number" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][right_reps]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.right_reps", $set->right_reps) }}">
                </div>
            </div>
        </div>
    @elseif($exercise->is_timed)
        <div class="form-group">
            <label>Duration (seconds)</label>
            <input type="number" class="form-control"
                   name="sets[{{ $exercise->id }}][{{ $setNumber }}][seconds]"
                   value="{{ old("sets.{$exercise->id}.{$setNumber}.seconds", $set->duration_seconds) }}">
        </div>
    @else
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][weight]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.weight", $set->right_weight ?? $set->left_weight) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Reps</label>
                    <input type="number" class="form-control"
                           name="sets[{{ $exercise->id }}][{{ $setNumber }}][reps]"
                           value="{{ old("sets.{$exercise->id}.{$setNumber}.reps", $set->right_reps ?? $set->left_reps) }}">
                </div>
            </div>
        </div>
    @endif

</div>
