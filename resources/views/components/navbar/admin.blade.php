<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-4 fixed-start ms-3"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap py-0 justify-content-between"
            href="{{route('dashboard')}}">
            <img src="{{asset('assets/img/site_logo.png')}}" class="navbar-brand-img h-100 max-h-3rem" alt="...">

        </a>
        <p class="font-weight-bolder ms-3 m-2 custom-margin-left">Welcome {{Auth::user()->name}}</p>

    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto overflow-visible" id="sidenav-collapse-main">
        <ul class="navbar-nav custom_admin_list">
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
                        <img src="{{asset('assets/img/SVG/adminTitles.svg')}}" alt="Titles" />
                    </div>
                    <span class="nav-link-text ms-1">Titles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('superadmin.buyerRequests')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/buyerRequests.svg')}}" alt="Buyers Requests" />

                    </div>
                    <span class="nav-link-text ms-1">Buyers Requests</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('superadmin.buyerlist')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/buyersAdmin.svg')}}" alt="Buyers" />
                    </div>
                    <span class="nav-link-text ms-1">Buyers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('superadmin.sellerlist')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/sellersAdmin.svg')}}" alt="Sellers" />
                    </div>
                    <span class="nav-link-text ms-1">Sellers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{route('superadmin.titleRequests')}}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <img src="{{asset('assets/img/SVG/buyerRequests.svg')}}" alt="Buyers Requests" />

                    </div>
                    <span class="nav-link-text ms-1">Orders</span>
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

