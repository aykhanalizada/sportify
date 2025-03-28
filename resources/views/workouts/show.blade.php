@extends('layout.app')

@push('styles')
    <style>
        .icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        .alert-gradient-light {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            border-left: 4px solid #4b79cf;
        }
        .badge-gradient-info {
            background: linear-gradient(to right, #1e88e5, #0d47a1);
        }
        .badge-gradient-warning {
            background: linear-gradient(to right, #ffb74d, #fb8c00);
        }
        .badge-gradient-success {
            background: linear-gradient(to right, #66bb6a, #43a047);
        }
        .badge-gradient-primary {
            background: linear-gradient(to right, #4b6cb7, #182848);
        }
    </style>
@endpush

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-dumbbell"></i>
                </span> Workout Details - {{ $workout->date->format('M d, Y') }}
                </h3>
                <a href="{{ route('workouts.index') }}" class="btn btn-gradient-light btn-fw">
                    <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">Workout Summary</h4>
                                <div class="badge badge-gradient-info">
                                    {{ $workout->sets->groupBy('exercise_id')->count() }} Exercises
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-gradient-primary text-white me-3 p-2 rounded">
                                            <i class="mdi mdi-weight mdi-24px"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Total Volume</p>
                                            <h4 class="mb-0">{{ number_format($workout->total_volume) }} {{ $workout->has_bodyweight ? 'reps' : 'kg' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-gradient-success text-white me-3 p-2 rounded">
                                            <i class="mdi mdi-repeat mdi-24px"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Total Sets</p>
                                            <h4 class="mb-0">{{ $workout->sets->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box bg-gradient-info text-white me-3 p-2 rounded">
                                            <i class="mdi mdi-calendar mdi-24px"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Workout Date</p>
                                            <h4 class="mb-0">{{ $workout->date->format('M d, Y') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($workout->note)
                                <div class="alert alert-gradient-light mb-4">
                                    <h6 class="alert-heading">Workout Notes</h6>
                                    <p class="mb-0">{{ $workout->note }}</p>
                                </div>
                            @endif

                            <h4 class="card-title mb-3">Exercises</h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Exercise</th>
                                        <th>Type</th>
                                        <th>Sets</th>
                                        <th>Details</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($workout->sets->groupBy('exercise_id') as $exerciseId => $sets)
                                        @php
                                            $exercise = $sets->first()->exercise;
                                            $isTimed = $exercise->is_timed;
                                            $isBodyweight = $exercise->is_bodyweight;
                                            $isUnilateral = $exercise->movement === 'unilateral';
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $exercise->name }}</strong>
                                            </td>
                                            <td>
                                                @if($isTimed)
                                                    <span class="badge badge-gradient-warning">Timed</span>
                                                @elseif($isUnilateral)
                                                    <span class="badge badge-gradient-info">Unilateral</span>
                                                @else
                                                    <span class="badge badge-gradient-success">Bilateral</span>
                                                @endif
                                                @if($isBodyweight)
                                                    <span class="badge badge-gradient-primary mt-1">Bodyweight</span>
                                                @endif
                                            </td>
                                            <td>{{ $sets->count() }}</td>
                                            <td>
                                                @foreach($sets as $set)
                                                    <div class="mb-1">
                                                        @if($isTimed)
                                                            <span class="text-muted">Set {{ $loop->iteration }}:</span>
                                                            {{ $set->duration_seconds }} seconds
                                                        @elseif($isUnilateral)
                                                            <span class="text-muted">Set {{ $loop->iteration }}:</span>
                                                            L: {{ $set->left_reps }} reps
                                                            <br>
                                                            R: {{ $set->right_reps }} reps
                                                        @else
                                                            <span class="text-muted">Set {{ $loop->iteration }}:</span>
                                                            {{ $set->reps }} reps
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($isTimed)
                                                    {{ $sets->sum('duration_seconds') }} sec
                                                @elseif($isBodyweight)
                                                    {{ $sets->sum(function($set) use ($isUnilateral) {
                                                        return $isUnilateral ? ($set->left_reps + $set->right_reps) : $set->reps;
                                                    }) }} reps
                                                @else
                                                    {{ number_format($sets->sum(function($set) use ($isUnilateral) {
                                                        return $isUnilateral ?
                                                            ($set->left_weight * $set->left_reps + $set->right_weight * $set->right_reps) :
                                                            ($set->weight * $set->reps);
                                                    })) }} kg
                                                @endif
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
