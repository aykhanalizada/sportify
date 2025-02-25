@extends('layout.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                  <i class="mdi mdi-home"></i>
                </span> Istifadəçi Məlumat Səhifəsi
                </h3>

                <a href="{{ route('muscle-group.create') }}" class="btn btn-gradient-success btn-fw">Create</a>
            </div>


            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Adı</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($muscleGroups as $muscleGroup)
                                        <tr>
                                            <td>{{$muscleGroup->name}}</td>
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
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
             <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024
                 <a
                     href="https://pharmed.az/"
                     target="_blank">Pharmed</a>. All rights reserved.</span>

            </div>
        </footer>
        <!-- partial -->
    </div>

@endsection
