@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Exercise</h4>
                        <form class="forms-sample" action="{{ route('exercises.store') }}" method="POST"
                              id="exerciseForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Enter exercise name" required>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Movement Type</label>
                                <select class="form-control" name="movement" id="movement-type" required>
                                    <option value="">Select Movement Type</option>
                                    <option value="bilateral">Bilateral (both sides together)</option>
                                    <option value="unilateral">Unilateral (one side at a time)</option>
                                </select>
                                @error('movement')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch ms-5">
                                    <input class="form-check-input" type="checkbox" name="is_bodyweight"
                                           id="is_bodyweight" value="1">
                                    <label class="form-check-label" for="is_bodyweight">Bodyweight Exercise</label>
                                </div>
                                @error('is_bodyweight')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check form-switch ms-5">
                                    <input class="form-check-input" type="checkbox" name="is_timed" id="is_timed"
                                           value="1">
                                    <label class="form-check-label" for="is_timed">Timed Exercise</label>
                                </div>
                                @error('is_timed')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label">Muscle Groups</label>
                                <div id="muscle-groups-container">
                                    <div class="muscle-group-row row mb-2">
                                        <div class="col-md-6">
                                            <select class="form-control muscle-group-select" name="muscle_groups[0][id]"
                                                    required>
                                                <option value="">Select Muscle Group</option>
                                                @foreach($muscleGroups as $muscleGroup)
                                                    <option value="{{ $muscleGroup->id }}">
                                                        {{ $muscleGroup->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-control level-select" name="muscle_groups[0][level]"
                                                    required>
                                                <option value="primary">Primary</option>
                                                <option value="secondary">Secondary</option>
                                                <option value="tertiary">Tertiary</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-muscle-group"
                                                    disabled>
                                                <i class="mdi mdi-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-muscle-group" class="btn btn-success btn-sm mt-2">
                                    <i class="mdi mdi-plus"></i> Add Muscle Group
                                </button>
                                @error('muscle_groups')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                                <a href="{{ route('exercises.index') }}" class="btn btn-light">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('muscle-groups-container');
            const addBtn = document.getElementById('add-muscle-group');
            let counter = 1;

            // Add new muscle group row
            addBtn.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.className = 'muscle-group-row row mb-2';
                newRow.innerHTML = `
                    <div class="col-md-6">
                        <select class="form-control muscle-group-select" name="muscle_groups[${counter}][id]" required>
                            <option value="">Select Muscle Group</option>
                            @foreach($muscleGroups as $muscleGroup)
                <option value="{{ $muscleGroup->id }}">{{ $muscleGroup->name }}</option>
                            @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-control level-select" name="muscle_groups[${counter}][level]" required>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="tertiary">Tertiary</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-muscle-group">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(newRow);
                counter++;

                // Enable remove buttons if there's more than one row
                if (container.children.length > 1) {
                    document.querySelectorAll('.remove-muscle-group').forEach(btn => {
                        btn.disabled = false;
                    });
                }
            });

            // Remove muscle group row
            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-muscle-group') ||
                    e.target.closest('.remove-muscle-group')) {
                    const row = e.target.closest('.muscle-group-row');
                    if (container.children.length > 1) {
                        row.remove();

                        // Disable remove button if only one row left
                        if (container.children.length === 1) {
                            container.querySelector('.remove-muscle-group').disabled = true;
                        }
                    }
                }
            });

            // Toggle between timed and bodyweight options
            const isTimedCheckbox = document.getElementById('is_timed');
            const isBodyweightCheckbox = document.getElementById('is_bodyweight');

            isTimedCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    isBodyweightCheckbox.checked = true;
                    isBodyweightCheckbox.disabled = true;
                } else {
                    isBodyweightCheckbox.disabled = false;
                }
            });
        });
    </script>
@endpush
