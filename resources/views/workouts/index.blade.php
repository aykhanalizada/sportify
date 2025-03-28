@extends('layout.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Workouts
                </h3>

                <a href="{{ route('workouts.create') }}" class="btn btn-gradient-success btn-fw">Create</a>
            </div>

            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Exercises</th>
                                        <th>Sets</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workouts as $workout)
                                        <tr>
                                            <td>{{ $workout->date->format('M d, Y') }}</td>
                                            <td>{{ $workout->sets->groupBy('exercise_id')->count() }}</td>
                                            <td>{{ $workout->sets->count() }}</td>
                                            <td class="text-right">
                                                <div class="btn-group" role="group">
                                                    <!-- Show Button -->
                                                    <a href="{{ route('workouts.show', $workout->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="View Details">
                                                        <span class="mdi mdi-eye"></span> View
                                                    </a>
                                                    &nbsp;
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('workouts.edit', $workout->id) }}"
                                                       class="btn btn-sm btn-primary"
                                                       title="Edit Workout">
                                                        <span class="mdi mdi-file-edit-outline"></span> Edit
                                                    </a>
                                                    &nbsp;
                                                    <!-- Delete Button -->
                                                    <form action="{{ route('workouts.destroy', $workout->id) }}"
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this?')"
                                                                title="Delete Workout">
                                                            <span class="mdi mdi-delete"></span> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
