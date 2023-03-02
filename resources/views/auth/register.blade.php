@extends('layouts/fullLayoutMaster')

@section('title', 'Register Page')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')

<div class="auth-wrapper auth-v1 px-2 ">
  <div class="auth-inner py-2">
    <!-- Register v1 -->
    <div class="card mb-0">
      <div class="card-body">
        <x-logo />
		
        {{-- <h4 class="card-title mb-1">Adventure starts here 🚀</h4> --}}
        {{-- <p class="card-text mb-2">Make your app management easy and fun!</p> --}}

        <form class="auth-register-form mt-2 reg-form" data-btnload="true" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="row">
		  
			<h4 class="card-title mb-1 form-head">Register Now</h4>		  
            <div class="form-group col-12 col-md-6">
              <label for="register-name" class="form-label">First Name</label>
              <input required type="text" class="form-control @error('name') is-invalid @enderror" id="register-name" name="name" placeholder="First Name" aria-describedby="register-name" tabindex="1" autofocus value="{{ old('name') }}" />
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="form-group col-12 col-md-6">
              <label for="register-last_name" class="form-label">Last Name</label>
              <input required type="text" class="form-control @error('last_name') is-invalid @enderror" id="register-last_name" name="last_name" placeholder="Last Name" aria-describedby="register-userlast_name" tabindex="2" value="{{ old('last_name') }}" />
              @error('last_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

          </div>
          <div class="form-group">
            <label for="register-email" class="form-label">Email</label>
            <input required type="email" class="form-control @error('email') is-invalid @enderror" id="register-email" name="email" placeholder="Email Address" aria-describedby="register-email" tabindex="3" value="{{ old('email') }}" />
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{!! $message !!}</strong>
              </span>
            @enderror
            <small class='d-block'><i>Registering with an academic email address (.edu) qualifies for a discounted subscription rate.</i></small>
          </div>

          <div class="form-group">
            <label for="register-password" class="form-label">Password {!! Helper::tooltipInfo("Password must be atleast 6 characters.") !!}</label>

            <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
              <input type="password" minlength="6" class="form-control form-control-merge @error('password') is-invalid @enderror" id="register-password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3" />
              <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="register-password-confirm" class="form-label">Confirm Password</label>

            <div class="input-group input-group-merge form-password-toggle">
              <input type="password" class="form-control form-control-merge" id="register-password-confirm" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3" />
              <div class="input-group-append">
                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
              </div>
            </div>
          </div>
          

          <div class="form-group">
            <small>By clicking Sign Up, you agree to our <a target="_blank" href="{{ config('setting.terms_url') }}" class="term-btn custom-login-link">Terms of use</a> & <a target="_blank" href="{{ config('setting.privacy_url') }}" class="privacy-btn custom-login-link">Privacy Policy</a>.</small>
          </div>
          <button type="submit" class="btn btn-primary btn-block sign-up-btn" tabindex="5">Sign up</button>
        </form>

        <p class="text-center mt-2">
          <span>Already have an account?</span>
          @if (Route::has('login'))
          <a href="{{ route('login') }}" class="accunt-btn custom-login-link">
            <span>Sign in instead</span>
          </a>
          @endif
        </p>
      </div>
    </div>
    <!-- /Register v1 -->
  </div>
</div>
@endsection


@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
{{-- <script src="{{asset(mix('js/scripts/pages/page-auth-register.js'))}}"></script> --}}
<script>
      // $(".auth-register-form").submit(function(e) {
      //   if( $(this).valid() ) {
      //     submitLoader($(this).find('input[type=submit]'));
      //   }
      // });

      $(".auth-register-form").validate({
        rules: {
          password_confirmation: {
            equalTo: "#register-password"
          }
        }
      });
</script>
@endsection