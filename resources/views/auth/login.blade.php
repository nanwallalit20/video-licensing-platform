@extends('layouts.guest')
@section('content')
  <section class="min-vh-100 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg" style="background-image: url('{{asset('/assets/img/curved-images/curved14.jpg')}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Welcome back</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Login Now</h5>
            </div>
            <div class="card-body">
              <form role="form text-left" method="POST" action="{{route('login')}}">
                @csrf
                <label>Email</label>
                <div class="mb-3">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Email"  aria-label="Email"  autocomplete="on" aria-describedby="email-addon">
                  @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
                <label>Password</label>
                <div class="mb-3">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="on" aria-label="Password" aria-describedby="password-addon">
                  @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign in</button>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <small class="text-muted">Forgot you password? Reset you password
                      <a href="{{route('password.request')}}" class="text-dark font-weight-bolder">here</a>
                    </small>
                      <p class="mb-4 text-sm mx-auto">
                        Don't have an account?
                        <a href="{{route('register')}}" class="text-dark font-weight-bolder">Sign up</a>
                      </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
