@extends('layouts.guest')
@section('content')
  <section class="min-vh-100 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg" style="background-image: url('{{asset('/assets/img/curved-images/curved14.jpg')}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Change password</h5>
            </div>
            <div class="card-body">
                <form role="form" action="{{route('password.update')}}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div>
                        <label for="email">Email</label>
                        <div class="">
                            <input id="email" name="email" type="email" class="form-control" value="{{$email}}"
                            placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                            @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="password">New Password</label>
                        <div class="">
                            <input id="password" name="password" autocomplete="off" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                            @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="">
                            <input id="password-confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Password-confirmation" aria-label="Password-confirmation" aria-describedby="Password-addon">
                            @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Set Password</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
