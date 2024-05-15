@extends('layouts.blankLayout')

@section('title', 'Register Basic - Pages')

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection


@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register Card -->
      <div class="card">
        <div class="card-body">
           <!-- Logo -->
           <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand">
                <img src="{{ asset('assets/img/icons/logo.png') }}" alt="Your Logo" class="app-brand-logo">
            </a>
        </div>
        <!-- /Logo -->

          <form id="formAuthentication" class="mb-3" action="{{ route('register')}}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="username" class="form-label">Full Name</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="John Doe" autofocus>
              @error('name')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="john@example.com">
              @error('email')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-3">
              <label for="username" class="form-label">Phone</label>
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder="+254.." autofocus>
              @error('phone')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('password')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('password_confirmation')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                <label class="form-check-label" for="terms-conditions">
                  I agree to
                  <a href="javascript:void(0);">privacy policy & terms</a>
                </label>
              </div>
            </div>
            <button class="btn btn-primary d-grid w-100">
              Sign up
            </button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{url('/login')}}">
              <span>Sign in instead</span>
            </a>
          </p>
        </div>
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>
@endsection
