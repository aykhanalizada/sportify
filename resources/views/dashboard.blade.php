@extends('layout.app')
@section('content')
    <style>
        #workoutFrequencyChart, #muscleGroupChart,
        #volumeChart, #topExercisesChart {
            width: 100% !important;
            height: 300px;
        }
    </style>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-chart-line"></i>
                </span> Workout Analytics Dashboard
                </h3>
            </div>

            <div class="row">
                <!-- Workout Frequency Chart -->
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Workout Frequency</h4>
                            <canvas id="workoutFrequencyChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Exercise Type Distribution -->
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Exercise Type Distribution</h4>
                            <canvas id="exerciseTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Volume Over Time Chart -->
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Volume Over Time</h4>
                            <canvas id="volumeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Exercises Chart -->
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Top Exercises</h4>
                            <canvas id="topExercisesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Workouts -->
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Workouts</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Exercises</th>
                                        <th>Total Sets</th>
                                        <th>Total Volume</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentWorkouts as $workout)
                                        <tr>
                                            <td>{{ $workout->date->format('M d, Y') }}</td>
                                            <td>{{ $workout->sets->groupBy('exercise_id')->count() }}</td>
                                            <td>{{ $workout->sets->count() }}</td>
                                            <td>
                                                @if($workout->has_bodyweight)
                                                    {{ $workout->total_volume }} reps
                                                @else
                                                    {{ number_format($workout->total_volume) }} kg
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('workouts.show', $workout->id) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> View
                                                </a>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Workout Frequency Chart (unchanged)
            const freqCtx = document.getElementById('workoutFrequencyChart').getContext('2d');
            new Chart(freqCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($workoutFrequency['labels']) !!},
                    datasets: [{
                        label: 'Workouts per Week',
                        data: {!! json_encode($workoutFrequency['data']) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    aspectRatio: 1.5,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // New: Exercise Type Distribution Chart
            const typeCtx = document.getElementById('exerciseTypeChart').getContext('2d');
            new Chart(typeCtx, {
                type: 'pie',
                data: {
                    labels: ['Bilateral', 'Unilateral', 'Timed', 'Bodyweight'],
                    datasets: [{
                        data: [
                            {!! $exerciseTypeDistribution['bilateral'] !!},
                            {!! $exerciseTypeDistribution['unilateral'] !!},
                            {!! $exerciseTypeDistribution['timed'] !!},
                            {!! $exerciseTypeDistribution['bodyweight'] !!}
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    aspectRatio: 1.5
                }
            });

            // Volume Over Time Chart (modified for bodyweight exercises)
            const volumeCtx = document.getElementById('volumeChart').getContext('2d');
            new Chart(volumeCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($volumeOverTime['labels']) !!},
                    datasets: [{
                        label: 'Weighted Volume (kg)',
                        data: {!! json_encode($volumeOverTime['weighted_data']) !!},
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    },
                        {
                            label: 'Bodyweight Volume (reps)',
                            data: {!! json_encode($volumeOverTime['bodyweight_data']) !!},
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            tension: 0.1,
                            fill: true
                        }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Top Exercises Chart (modified for new structure)
            const exercisesCtx = document.getElementById('topExercisesChart').getContext('2d');
            new Chart(exercisesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topExercises['labels']) !!},
                    datasets: [{
                        label: 'Total Sets',
                        data: {!! json_encode($topExercises['data']) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
