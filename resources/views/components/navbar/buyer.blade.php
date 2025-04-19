<nav class="col-md-2 sidebar custom-sidebar py-4 px-0">
    <div class="d-flex flex-column">
        <a class="align-items-center d-flex mb-2 navbar-brand text-wrap logo-img px-4 justify-content-center" href="">
            <img src="{{asset('assets/img/site_logo.png')}}" class="navbar-brand-img h-100 max-h-3rem" alt="...">
        </a>
        <p class="font-weight-bolder custom-margin-left">Welcome {{Auth::user()->name}}</p>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link px-4 active" href="{{route('dashboard')}}">Titles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4" href="{{route('buyer.my-content')}}">My Content ({{$totalTitles}})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4" href="{{ route('logout') }}" data-method="post">Logout</a>
            </li>
        </ul>
    </div>
</nav>
