@extends('layouts/fullLayoutMaster')

@section('title', 'Email Verify')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
@endsection

@section('content')
    <div class="auth-wrapper auth-v1 px-2">
        <div class="auth-inner py-2">
          <div class="card mb-0">
              <div class="card-body">
                  <x-logo />
                  @if (session()->has('resent'))
                      <div class="row">
                          <div class="col-12 mb-75">
                              <div class="alert alert-success mb-50" role="alert">
                                  <h4 class="alert-heading">A fresh verification link has been sent to your email
                                      address.</h4>
                              </div>
                          </div>
                      </div>
                  @endif
                  <div class="col-12 mt-75 text-center">
                      <h4 class='text-center'>Please verify your email</h4>
                      <p>You're almost there! We sent an email to<br><strong>{{ auth()->user()->email }}</strong>
                      </p>
                      <p>
                          Just click on the link in that email to continue. If you don't see it, you may need to
                          <strong>check your spam</strong> folder.
                      </p>
                      <p>
                          Still can't find the email?
                      </p>
                      <div class="alert-body">
                          {{-- <a href="javascript: void(0);" class="resend-email-verification btn btn-primary">
                              Resend confirmation
                          </a> --}}
                          <form data-btnload="true" id="resend-email-form" method="POST"
                              action="{{ route('verification.resend') }}">
                              @csrf
                              <button type="submit" class="btn btn-primary">Resend confirmation</button>
                          </form>
                      </div>
                      <div class="mt-1">
                        <form data-btnload="true" id="resend-email-form" method="POST"
                        action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link custom-login-link">Logout</button>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        //  Resend email
        $(".resend-email-verification").click(function() {
            $("#resend-email-form").submit();
        });

    </script>
@endsection
