@extends('layouts.guest')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
@endsection
@section('content')

  <section class="min-vh-100 mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg" style="background-image: url('{{asset('/assets/img/curved-images/curved14.jpg')}}');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Welcome!</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Register Now</h5>
            </div>
            <div class="card-body">
              <form role="form text-left" class="ajaxForm register-form" id="register-form" method="POST" action="{{route('register')}}">
                @csrf
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Name" name="name" id="name" aria-label="Name" aria-describedby="name" value="{{ old('name') }}">
                  @error('name')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Email Address" name="email" id="email" aria-label="email" aria-describedby="email" value="{{ old('email') }}">
                    @error('email')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3 custom_phone_field">
                    <input type="text" class="form-control" placeholder="Mobile Number" name="phone" id="fullPhoneNumber" aria-label="phone" aria-describedby="phone" value="{{ old('phone') }}">
                    <input type="hidden" id="countryCode" name="country_code">
                    <span class="invalid-feedback-mobile-validation"  id="error-msg" style="display:none;">Please enter a valid phone number.</span>
                    @error('phone')
                      <p class="text-danger text-xs mt-2" >{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                  <input type="number" class="form-control" placeholder="How many films would you like to submit?" name="movies_count" id="movies_count" aria-label="movies_count" aria-describedby="movies_count-addon" value="{{ old('movies_count') }}">
                  @error('movies_count')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="What is your role in the project?" name="project_role" id="project_role" aria-label="project_role" aria-describedby="project_role-addon" value="{{ old('project_role') }}">
                    @error('project_role')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password" id="password" aria-label="Password" aria-describedby="password-addon">
                  @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" id="password-confirm" aria-label="Password" aria-describedby="password-addon">
                  </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
                </div>
                <p class="text-sm mt-3 mb-0">Already have an account? <a href="login" class="text-dark font-weight-bolder">Sign in</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="{{asset('js/register.js')}}"></script>
@endsection
