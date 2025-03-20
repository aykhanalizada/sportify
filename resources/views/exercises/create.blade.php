@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Exercise</h4>
                        <form class="forms-sample" action="{{ route('exercises.store') }}" method="POST">
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
                                <label for="muscle_groups" class="form-label">Muscle Groups</label>
                                <select class="form-control choices-select" name="muscle_groups[]"
                                        multiple>
                                    @foreach($muscleGroups as $muscleGroup)
                                        <option value="{{ $muscleGroup->id }}">
                                            {{ $muscleGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('muscle_groups')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="muscle_groups" class="form-label">Unilateral</label>
                                <select class="form-control choices-select" name="is_unilateral">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="muscle_groups" class="form-label">Timed</label>
                                <select class="form-control choices-select" name="is_timed">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
