@extends('layout.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Muscle Groups
                </h3>

                <a href="{{ route('muscle-groups.create') }}" class="btn btn-gradient-success btn-fw">Create</a>
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
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($muscleGroups as $muscleGroup)
                                        <tr>
                                            <td>{{$muscleGroup->name}}</td>
                                            <td class="text-right">
                                                <a href="{{ route('muscle-groups.edit', $muscleGroup->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    <span class="mdi mdi-file-edit-outline"></span> Edit
                                                </a>
                                                <form action="{{ route('muscle-groups.destroy', $muscleGroup->id) }}"
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
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- content-wrapper ends -->

    </div>

@endsection
