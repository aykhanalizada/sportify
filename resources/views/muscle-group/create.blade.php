@extends('layout.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-5">Create Muscle Group</h4>
                        <form class="forms-sample" action="{{ route('muscle-group.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Enter muscle group name" required>
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
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
