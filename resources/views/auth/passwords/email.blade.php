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
            @if(session('status'))
                <div class="m-3  alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                    <span class="alert-text text-white">
                    {{ session('status') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
            <div class="card-header text-center pt-4">
              <h5>Forgot your password? Enter your email here</h5>
            </div>
            <div class="card-body">
              <form role="form text-left" method="POST" action="{{route('password.request')}}">
                @csrf
                <label for="email">Email</label>
                <div class="">
                    <input id="email" name="email" type="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                    @error('email')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Recover your password</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

