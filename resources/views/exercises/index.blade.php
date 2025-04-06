@extends('layout.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-dumbbell"></i>
                </span> Exercises
                </h3>
                <a href="{{ route('exercises.create') }}" class="btn btn-gradient-success btn-fw">Create</a>
            </div>

            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Movement</th>
                                        <th>Bodyweight</th>
                                        <th>Timed</th>
                                        <th>Uses Band</th> <!-- New column header -->
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($exercises as $exercise)
                                        <tr>
                                            <td>{{ $exercise->name }}</td>
                                            <td>
                                                @if($exercise->is_timed)
                                                    <span class="badge badge-warning">Timed</span>
                                                @elseif($exercise->is_bodyweight)
                                                    <span class="badge badge-primary">Bodyweight</span>
                                                @else
                                                    <span class="badge badge-success">Weighted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($exercise->movement == 'unilateral')
                                                    <span class="badge badge-info">Unilateral</span>
                                                @else
                                                    <span class="badge badge-secondary">Bilateral</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($exercise->is_bodyweight)
                                                    <span class="mdi mdi-check-circle text-success h4"></span>
                                                @else
                                                    <span class="mdi mdi-close-circle text-danger h4"></span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($exercise->is_timed)
                                                    <span class="mdi mdi-check-circle text-success h4"></span>
                                                @else
                                                    <span class="mdi mdi-close-circle text-danger h4"></span>
                                                @endif
                                            </td>
                                            <td> <!-- New column data -->
                                                @if($exercise->uses_band)
                                                    <span class="mdi mdi-check-circle text-success h4"></span>
                                                @else
                                                    <span class="mdi mdi-close-circle text-danger h4"></span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('exercises.edit', $exercise->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <span class="mdi mdi-file-edit-outline"></span> Edit
                                                </a>
                                                <form action="{{ route('exercises.destroy', $exercise->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this?')">
                                                        <span class="mdi mdi-delete"></span> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($exercises->isEmpty())
                                <div class="text-center py-4">
                                    <p class="text-muted">No exercises found. Create your first exercise!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
