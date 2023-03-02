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
            max-width: 1024px!important;
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

                    <form data-btnload="true" class="auth-register-form mt-2" method="POST" action="{{ route('apply.presenter') }}">
                        @csrf

                        @include('inc.presenter.apply', [
                            'userMeta' => $userMeta,
                            'userAddress' => $userAddress
                        ])

                        <div class="row justify-content-center">
                            <div class="col-md-6 col-12 text-center">
                                <div class="form-group">
                                    <small>By clicking Submit, you agree to our <a target="_blank"
                                            href="{{ config('setting.terms_url') }}">Terms of use</a> & <a target="_blank"
                                            href="{{ config('setting.privacy_url') }}">Privacy Policy</a>.</small>
                                </div>
                                <button type="submit" class="btn btn-primary" tabindex="5">Submit</button>
                                <p class="text-center mt-1">
                                    <a href="{{ route('login') }}">
                                        <span>Back to Dashboard</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>

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
