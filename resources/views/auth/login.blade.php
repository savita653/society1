@extends('layouts/fullLayoutMaster')

@section('title', 'Login Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-v1 px-2">
  <div class="auth-inner py-2">
    <!-- Login v1 -->
    <div class="card mb-0">
      <div class="card-body">
        <x-logo />

        {{-- <h4 class="card-title mb-1 ">Welcome to {{ config('app.name') }}! 👋</h4> --}}
        {{-- <p class="card-text mb-2">Please sign-in to your account and start the adventure</p> --}}
        @if(session('account_inactive'))
        <p class="text-danger">Your account in inactive, <a href="{{ config('setting.conatact_url') }}">Click Here</a> to contact for more information.</p>
        @endif
        <form data-btnload="true" class="auth-login-form mt-2" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
            <label for="login-email" class="form-label">Email</label>
            <input required type="text" class="form-control @error('email') is-invalid @enderror" id="login-email" name="email" placeholder="john@example.com" aria-describedby="login-email" tabindex="1" autofocus value="{{ old('email', request()->get('email')) }}" />
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <div class="d-flex justify-content-between">
              <label for="login-password">Password</label>
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="forgot-link custom-login-link">
                <small>Forgot Password?</small>
              </a>
              @endif
            </div>
            <div class="input-group input-group-merge form-password-toggle">
              <input required type="password" class="form-control form-control-merge" id="login-password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
              <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input class="custom-control-input" type="checkbox" id="remember-me" name="remember-me" tabindex="3" {{ old('remember-me') ? 'checked' : '' }} />
              <label class="custom-control-label" for="remember-me"> Remember Me </label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block" tabindex="4">Sign in</button>
        </form>

        <p class="text-center mt-2">
          <span>New on our platform?</span>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="register-link custom-login-link">
            <span>Create an account</span>
          </a>
          @endif
        </p>
        <p class="text-center mt-2">
          <span>Interested in Presenting?</span>
          <a href="{{ route('become-a-presenter') }}" class="presenter-link custom-login-link">
            <span>Start Here</span>
          </a>
        </p>

      </div>
    </div>
    <!-- /Login v1 -->
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/page-auth-login.js'))}}"></script>
@endsection