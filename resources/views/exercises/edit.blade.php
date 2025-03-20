@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Exercise</h4>
                        <form class="forms-sample" action="{{ route('exercises.update',$exercise->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Enter exercise name" required value="{{$exercise->name}}">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="muscle_groups" class="form-label">Muscle Groups</label>
                                <select class="form-control choices-multiple" id="muscle_groups" name="muscle_groups[]"
                                        multiple required>
                                    @foreach($muscleGroups as $muscleGroup)
                                        <option value="{{ $muscleGroup->id }}"
                                                @if($exercise->muscleGroups->contains($muscleGroup->id) ) selected @endif>
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
                                    <option {{$exercise->is_unilateral == 1 ? 'selected' : ''}} value="1">Yes</option>
                                    <option {{$exercise->is_unilateral == 0 ? 'selected' : ''}}  value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="muscle_groups" class="form-label">Timed</label>
                                <select class="form-control choices-select" name="is_timed">
                                    <option {{$exercise->is_timed == 1 ? 'selected' : ''}} value="1">Yes</option>
                                    <option {{$exercise->is_timed == 0 ? 'selected' : ''}}  value="0">No</option>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var muscleGroupsSelect = document.getElementById('muscle_groups');

            if (muscleGroupsSelect) {
                new Choices(muscleGroupsSelect, {
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: 'Select muscle groups',
                    classNames: {
                        containerOuter: 'choices',
                    }
                });
            }
        });
    </script>
@endpush
