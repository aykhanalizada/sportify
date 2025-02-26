<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sportify Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/vendors/font-awesome/css/font-awesome.min.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/vendors/font-awesome/css/font-awesome.min.css')}}"/>
    <link rel="stylesheet"
          href="{{asset('purple-free/dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('purple-free/dist/assets/css/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('purple-free/dist/assets/images/favicon.png')}}"/>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

</head>
<body>
<style>
    * {
        font-family: sans-serif !important;
    }
</style>
<div class="container-scroller">

    <!-- partial:partials/_navbar.html -->
    @include('layout.navbar')

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">

        <!-- partial:partials/_sidebar.html -->
        @include('layout.sidebar')

        <!-- partial -->
        @yield('content')
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<!-- plugins:js -->
<script src="{{asset('purple-free/dist/assets/vendors/js/vendor.bundle.base.js')}}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{asset('purple-free/dist/assets/vendors/chart.js/chart.umd.js')}}"></script>
<script src="{{asset('purple-free/dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{asset('purple-free/dist/assets/js/off-canvas.js')}}"></script>
<script src="{{asset('purple-free/dist/assets/js/misc.js')}}"></script>
<script src="{{asset('purple-free/dist/assets/js/settings.js')}}"></script>
<script src="{{asset('purple-free/dist/assets/js/todolist.js')}}"></script>
<script src="{{asset('purple-free/dist/assets/js/jquery.cookie.js')}}"></script>
<!-- endinject -->

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Stack scripts -->
@stack('scripts')

<!-- Custom js for this page -->
{{--<script src="purple-free/dist/assets/js/dashboard.js"></script>--}}
<!-- End custom js for this page -->

</body>
</html>
