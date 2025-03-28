<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{asset('img/company.png')}}" style="object-fit: cover" alt="profile"/>
                    <span class="login-status online"></span>
                    <!--change to offline or busy as needed-->
                </div>
                <div class=" d-flex flex-column">
                    <span class="font-weight-bold mb-2">
{{--                        {{ $user->name }}--}}
                    Admin
                    </span>
                    <span class="text-secondary text-small">User</span>
                </div>

            </a>
        </li>

        <li class="nav-item {{request()->url() == route('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('dashboard')}}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('workouts*') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('workouts.index')}}">
                <span class="menu-title">Workouts</span>
                <i class="mdi mdi-flash menu-icon"></i>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('exercises*') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('exercises.index')}}">
                <span class="menu-title">Exercise</span>
                <i class="mdi mdi-flash menu-icon"></i>
            </a>
        </li>


        <li class="nav-item {{ request()->routeIs('muscle-groups*') ? 'active' : '' }}">
            <a class="nav-link" href="{{route('muscle-groups.index')}}">
                <span class="menu-title">Muscle Group</span>
                <i class="mdi mdi-flash menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>
