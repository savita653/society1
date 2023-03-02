@extends('layouts/fullLayoutMaster')

@section('title', 'Become a Presenter')

@section('vendor-style')
    <!-- vendor css files -->
    @include('inc/select2/styles')

@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
    <style>
        .auth-inner-extend {
            max-width: 1024px !important;
        }

    </style>
@endsection

@section('content')

    <div class="auth-wrapper auth-v1 px-2 ">
        <div class="auth-inner auth-inner-extend py-2">
            <!-- Register v1 --> 
            <div class="card mb-0">
                <div class="card-body">
                    <x-logo />

                    <h4 class="card-title mb-1">Become a Presenter</h4>
                    {{-- <p class="card-text mb-2">Make your app management easy and fun!</p> --}}

                    <form data-btnload="true" class="auth-register-form mt-2" method="POST"
                        action="{{ route('become-a-presenter') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label for="register-name" class="form-label">First Name</label>
                                        <input required type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="register-name" name="name" placeholder="First Name" aria-describedby="register-name"
                                            tabindex="1" autofocus value="{{ old('name') }}" />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label for="register-last_name" class="form-label">Last Name</label>
                                        <input required type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="register-last_name" name="last_name" placeholder="Last Name"
                                            aria-describedby="register-userlast_name" tabindex="1"
                                            value="{{ old('last_name') }}" />
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="register-email" class="form-label">Email</label>
                                            <input required type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="register-email" name="email" placeholder="Email Address"
                                                aria-describedby="register-email" tabindex="1" value="{{ old('email') }}" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{!! $message !!}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
        
                                <div class="form-group">
                                    <label for="register-password" class="form-label">Password {!! Helper::tooltipInfo('Password must be atleast 6 characters.') !!}</label>
        
                                    <div
                                        class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
                                        <input type="password" minlength="6"
                                            class="form-control form-control-merge @error('password') is-invalid @enderror"
                                            id="register-password" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="register-password" tabindex="1" />
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
                                        <input type="password" class="form-control form-control-merge"
                                            id="register-password-confirm" name="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="register-password" tabindex="1" />
                                        <div class="input-group-append">
                                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        @include('inc.presenter.apply')

                        <div class="row justify-content-center">
                            <div class="col-12 text-center">
                                <div class="form-group">
                                    <small>
                                        By clicking Sign Up, you agree to our 
                                        <a class="term-link link-primary-btn" target="_blank"
                                            href="{{ config('setting.terms_url') }}">Terms of use</a> & <a class="privacy-link link-primary-btn" target="_blank"
                                            href="{{ config('setting.privacy_url') }}">Privacy Policy</a>.
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary" tabindex="5">Submit</button>
                            </div>
                        </div>
                    </form>

                    <p class="text-center mt-2">
                        <span>Already have an account?</span>
                        @if (Route::has('login'))
                            <a class="sign-btn link-primary-btn" href="{{ route('login') }}">
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>

    <!-- vendor css files -->
    @include('inc/select2/scripts')

@endsection

@section('page-script')
    {{-- <script src="{{ asset(mix('js/scripts/pages/page-auth-register.js')) }}"></script> --}}
    <script>
        $(document).ready(function() {

            applyPresenterScript();

            $(".auth-register-form").validate({
                rules: {
                    password_confirmation: {
                        equalTo: "#register-password"
                    },
                    email: {
                        remote: route('api.email.unique')
                    }
                }
            });


        });

    </script>
@endsection
