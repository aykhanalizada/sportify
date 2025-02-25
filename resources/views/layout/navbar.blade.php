<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
{{--        <a class="navbar-brand brand-logo" href="/">
            <img src="img/fitness-icon.jpg"
                 style="object-fit: cover"
                 alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini ps-4" href="--}}{{--{{route('dashboard')}}--}}{{--">
            <img
                style="object-fit: cover; width: 30px; height: 30px;"
                src="img/fitness-icon.jpg" alt="logo"/></a>--}}
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="#">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
                </div>
            </form>
        </div>
        <ul class="navbar-nav navbar-nav-right ">
            <li class="nav-item nav-profile dropdown ">
                <a class="nav-link dropdown-toggle " id="profileDropdown" href="#" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    <div class="nav-profile-img">
                        <img src="{{asset('img/company.png')}}" style="object-fit: cover" alt="image">

                    </div>
                    <div class="">
                        <p class="mb-1 mt-1 text-black">
                            Admin
                            {{--{{ $user->name }}--}}
                        </p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{--{{route('logout')}}--}}">
                        <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                </div>
            </li>
            <li class="nav-item d-none d-lg-block full-screen-link">
                <a class="nav-link">
                    <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                </a>
            </li>



        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
