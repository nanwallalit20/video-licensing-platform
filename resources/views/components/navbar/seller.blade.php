<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-4 fixed-start ms-3"
       id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
           aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap py-0 justify-content-between"
           href="{{ route('dashboard') }}">
            <img src="{{asset('assets/img/site_logo.png')}}" class="navbar-brand-img h-100 max-h-3rem" alt="...">

        </a>
        <p class="font-weight-bolder ms-3 m-2 custom-margin-left">Welcome {{Auth::user()->name}}</p>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto overflow-visible" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="{{route('dashboard')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/adminDashboard.svg')}}" alt="Dashboard" />
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('titles')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/title.svg')}}" alt="Movie Titles" />
                    </div>
                    <span class="nav-link-text ms-1">Movie Titles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('seller.analytics')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/sellerAnalytics.svg')}}" alt="Analytics" />
                    </div>
                    <span class="nav-link-text ms-1">Analytics</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('logout') }}" data-method="post">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/logout.svg')}}" alt="Logout" />
                    </div>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
            </li>

        </ul>
    </div>

</aside>

