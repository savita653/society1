@extends('layouts/contentLayoutMaster')

@section('title', 'Account Settings')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel='stylesheet' href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    {{-- <link rel='stylesheet' href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}"> --}}
@endsection
@section('page-style')
    <!-- Page css files -->
    {{-- <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}"> --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/cropper/cropper.min.css') }}">
@endsection

@section('content')
    <!-- account setting page -->
    <section id="page-account-settings">
        <div class="row">
            <!-- left menu section -->
            <div class="col-md-3 mb-2 mb-md-0">
                <ul class="nav nav-pills flex-column nav-left">
                    <!-- general -->
                    <li class="nav-item">
                        <a class="nav-link active" id="account-pill-general" data-toggle="pill"
                            href="#account-vertical-general" aria-expanded="true">
                            <i data-feather="user" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">General</span>
                        </a>
                    </li>
                    {{-- @hasanyrole('subscriber|presenter|super_admin') --}}
                        {{-- account-institution --}}
                        <li class="nav-item">
                            <a class="nav-link" id="account-pill-institution"  data-toggle="pill"
                                href="#account-institution" aria-expanded="false">
                                <i data-feather="briefcase" class="font-medium-3 mr-1"></i>
                                <span class="font-weight-bold">Institution Details</span>
                            </a>
                        </li>
                    {{-- @endhasanyrole --}}
                    <!-- change password -->
                    <li class="nav-item">
                        <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password"
                            aria-expanded="false">
                            <i data-feather="lock" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">Change Password</span>
                        </a>
                    </li>
                    <!-- change passemailword -->
                    <li class="nav-item">
                        <a  class="nav-link" id="account-pill-email" data-toggle="pill" href="#account-vertical-email"
                            aria-expanded="false">
                            <i data-feather="mail" class="font-medium-3 mr-1"></i>
                            <span class="font-weight-bold">Change Email</span>
                        </a>
                    </li>
                    @if(auth()->user()->subscribed('default'))
                        <li class="nav-item">
                            <a class="nav-link redirect-to"  data-toggle="pill"  data-href="{{ auth()->user()->billingPortalUrl(route('account')) }}" href="{{ auth()->user()->billingPortalUrl(route('account')) }}"
                                aria-expanded="false">
                                <i data-feather="dollar-sign" class="font-medium-3 mr-1"></i>
                                <span class="font-weight-bold mr-50">Manage Billing</span> 
                                <i data-feather='external-link'></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <!--/ left menu section -->

            <!-- right content section -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- general tab -->
                            <div role="tabpanel" class="tab-pane active" id="account-vertical-general"
                                aria-labelledby="account-pill-general" aria-expanded="true">
                                @if(session()->has('resent'))
                                    <div class="row">
                                        <div class="col-12 mb-75">
                                            <div class="alert alert-success mb-50" role="alert">
                                                <h4 class="alert-heading">A fresh verification link has been sent to your email address.</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                
                                <!-- header media -->
                                
                                <div class="media">
                                    <div id="user_image" href="javascript:void(0);" class="mr-25">
                                        <img src="{{ auth()->user()->profileImage() }}"
                                             class="rounded-circle mr-50" alt="profile image" height="80"
                                            width="80" />
                                    </div>
                                    <!-- upload and reset button -->
                                    <div class="media-body mt-75 ml-1">
                                        <input type="file" name="profile" class="d-none" accept="image/*" id="profile">
                                        <button type="button" class="btn  btn-primary btn-icon text-center"
                                            data-toggle="modal" id="change-photo" data-target="#modal_change_photo">Change
                                            Photo
                                        </button>
                                    </div>
                                    @include('inc.modal.crop_image', [
                                        'crop_url' => route('crop_image', 'web'),
                                        'upload_url' => route('upload_image', 'web'),
                                    ])
                                    <!--/ upload and reset button -->
                                </div>
                                <!--/ header media -->

                                <!-- form -->
                                <form action="{{ route('updateBasicInfo') }}" id="basic-info-form" method="post" class=" mt-2">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-name">First Name</label>
                                                <input required type="text" class="form-control" id="account-name" name="name"
                                                    placeholder="name" value="{{ auth()->user()->name }}" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-last_name">Last Name</label>
                                                <input required type="text" class="form-control" id="account-last_name" name="last_name"
                                                    placeholder="Last Name" value="{{ auth()->user()->last_name }}" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="account-timezone">Timezone</label>
                                                @php 
                                                    $userTimezone = auth()->user()->timezone; 
                                                @endphp
                                                <select required name="timezone" class="form-control select2 w-100" id="account-timezone">
                                                    <option value="">--Select Timezone--</option>
                                                    @foreach (\App\Timezone::all() as $timezone)
                                                        <option {{ $userTimezone == $timezone->name ? "selected" : "" }} value="{{ $timezone->name }}">
                                                            {{ $timezone->name }}
                                                            ({{ $timezone->offset }}) 
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="login-area_of_interest" class="form-label">Areas of Interest {!! Helper::tooltipInfo("Please check all that apply. To specify new keywords, Type your keyword and press enter.") !!}</label>
                
                                                @php
                                                    $selectedAreaOfInterest = !empty($userMeta['areas_of_interest']) ? json_decode($userMeta['areas_of_interest'], true) : [];
                                                    
                                                    $areasOfInterest = array_unique(array_merge(config('setting.area_of_interest_options'), $selectedAreaOfInterest));
                                                @endphp
                                                <div class="position-relative">
                                                    <select  multiple
                                                        class="form-control select-tags @error('area_of_interest') is-invalid @enderror"
                                                        id="login-area_of_interest" name="areas_of_interest[]"
                                                        aria-describedby="login-area_of_interest" tabindex="1">
                                                        @foreach ($areasOfInterest ?? [] as $value)
                                                            <option {{ in_array($value, $selectedAreaOfInterest) ? 'selected' : '' }}
                                                                value="{{ trim($value) }}">
                                                                {{ $value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('areas_of_interest')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                       

                                        <div class="col-12 mt-50">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                  <input {{ old('email_notification', $userMeta['email_notification'] ?? "") ? 'checked' : '' }} name="email_notification" type="checkbox" class="custom-control-input" id="email_notification" >
                                                  <label class="custom-control-label" for="email_notification">
                                                    
                                                    Would you like to receive email notifications of upcoming events?
                                                  </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                  <input {{ old('can_contact', auth()->user()->can_contact) ? 'checked' : '' }} name="can_contact" type="checkbox" class="custom-control-input" id="can_contact" >
                                                  <label class="custom-control-label" for="can_contact">
                                                    Would you like to be contacted by interested recruiters? 
                                                  </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input {{ old('newsletter', $userMeta['newsletter'] ?? "") ? 'checked' : '' }} name="newsletter" type="checkbox" class="custom-control-input" id="newsletter" >
                                                    <label class="custom-control-label" for="newsletter">
                                                    Subscribe to our newsletter to get latest news and updates.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <button id="basic-info-btn" type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                            <!--/ general tab -->
                            
                            {{-- Institution Details --}}
                            {{-- @hasanyrole('subscriber|presenter|super_admin') --}}
                                <div class="tab-pane fade" id="account-institution" role="tabpanel"
                                    aria-labelledby="account-pill-institution" aria-expanded="false">
                                    <form action="{{ route('institutionInfo') }}" id="institution-form" method="post">
                                        @csrf
                                        
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="login-institution_name" class="form-label">Institution Name</label>
                                                    <input required type="text" class="form-control @error('institution_name') is-invalid @enderror"
                                                        id="login-institution_name" name="institution_name" placeholder="Institution Name"
                                                        aria-describedby="login-institution_name" tabindex="1" autofocus
                                                        value="{{ old('institution_name', $userMeta['institution_name'] ?? "") }}" />
                                                    @error('institution_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="login-department" class="form-label">Department</label>
                                                    <input required type="text" class="form-control @error('department') is-invalid @enderror"
                                                        id="login-department" name="department" placeholder="Department"
                                                        aria-describedby="login-department" tabindex="1" autofocus
                                                        value="{{ old('department', $userMeta['department'] ?? "") }}" />
                                                    @error('department')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <label>Institution Address</label>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="login-street_name" class="form-label">Street Name</label>
                                                    <input required type="text" class="form-control @error('street_name') is-invalid @enderror"
                                                        id="login-street_name" name="street_name" placeholder="Street Name"
                                                        aria-describedby="login-street_name" tabindex="1" autofocus
                                                        value="{{ old('street_name', $userAddress['street_name'] ?? "") }}" />
                                                    @error('street_name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="login-city" class="form-label">City</label>
                                                    <input required type="text" class="form-control @error('city') is-invalid @enderror"
                                                        id="login-city" name="city" placeholder="City" aria-describedby="login-city"
                                                        tabindex="1" autofocus value="{{ old('city', $userAddress['city'] ?? "") }}" />
                                                    @error('city')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="login-state" class="form-label">State</label>
                                                    <input required type="text" class="form-control @error('state') is-invalid @enderror"
                                                        id="login-state" name="state" placeholder="State" aria-describedby="login-state"
                                                        tabindex="1" autofocus value="{{ old('state', $userAddress['state'] ?? "") }}" />
                                                    @error('state')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="login-postal_code" class="form-label">Postal Code</label>
                                                    <input required type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                                        id="login-postal_code" name="postal_code" placeholder="Postal Code"
                                                        aria-describedby="login-postal_code" tabindex="1" autofocus
                                                        value="{{ old('postal_code', $userAddress['postal_code'] ?? "") }}" />
                                                    @error('postal_code')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="login-country" class="form-label">Country</label>
                                                    <input required type="text" class="form-control @error('country') is-invalid @enderror"
                                                        id="login-country" name="country" placeholder="Country"
                                                        aria-describedby="login-country" tabindex="1" autofocus
                                                        value="{{ old('country', $userAddress['country'] ?? "") }}" />
                                                    @error('country')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button id="institution-info-btn" type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            {{-- @endhasanyrole --}}

                            <!-- change password -->
                            <div class="tab-pane fade" id="account-vertical-password" role="tabpanel"
                                aria-labelledby="account-pill-password" aria-expanded="false">
                                <!-- form -->
                                <form action="{{ route("changePassword") }}" method="post" id="change-password-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="current_password">Current Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input required type="password" class="form-control"
                                                        id="current_password" name="current_password"
                                                        placeholder="Current Password" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="password">New Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input required type="password" id="password"
                                                        name="password" class="form-control"
                                                        placeholder="New Password" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer">
                                                            <i data-feather="eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="password-confirmation">Retype New Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input type="password" class="form-control"
                                                        id="password-confirmation" name="password_confirmation"
                                                        placeholder="New Password" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text cursor-pointer"><i
                                                                data-feather="eye"></i></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button id="change-password-submit" type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                                            <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                                <!--/ form -->
                            </div>
                            <!--/ change password -->

                            <div class="tab-pane fade" id="account-vertical-email" role="tabpanel"
                                aria-labelledby="account-pill-email" aria-expanded="false">
                                <!-- form -->
                                <form id="change-email-form" method="post" action="{{ route('changeEmail') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-75">
                                            <strong>Current Email Address:</strong> 
                                            {{ auth()->user()->email }} 
                                            {!! auth()->user()->hasVerifiedEmail() ? '<span class="badge badge-success badge-pill">Verified</span>' : '<span class="badge badge-danger badge-pill">Not Verified</span>' !!}
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input required type="email" class="form-control" id="email" name="email"
                                                    placeholder="Email" value="" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="email-confirmation">Retype New Email</label>
                                                <input  type="email" class="form-control" id="email-confirmation"
                                                    name="email_confirmation" placeholder="Retype New Email" value="" />
                                            </div>
                                        </div>
                                        @if(!auth()->user()->hasVerifiedEmail())
                                            <div class="col-12 mt-75">
                                                <div class="alert alert-warning mb-50" role="alert">
                                                    <h4 class="alert-heading">Your email is not verified. please check your email for a verification link.</h4>
                                                    <div class="alert-body">
                                                        <a href="javascript: void(0);" class="alert-link resend-email-verification">Resend
                                                            confirmation</a>
                                                        <form id="resend-email-form" class="d-none" method="POST" action="{{ route('verification.resend') }}">
                                                            @csrf
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-12">
                                            <button id="change-email-submit" type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                                            <button type="reset" class="btn btn-outline-secondary mt-1">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                                <form id="resend-email-form" class="d-none" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                </form>
                                <!--/ form -->
                            </div>
                            <!--/ change email -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ right content section -->
        </div>
    </section>
    <!-- / account setting page -->
@endsection

@section('vendor-script')
    <!-- vendor files -->
    {{-- select2 min js --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    {{-- jQuery Validation JS --}}
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset('js/scripts/cropper/cropper.min.js') }}"></script>
	<script src="{{ asset('js/scripts/crop.js') }}"></script>
    {{-- <script src="{{ asset(mix('vendors/js/extensions/dropzone.min.js')) }}"></script> --}}
    {{-- <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script> --}}
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset('js/scripts/jquery.form.js') }}"></script>
    <script>
        $(document).ready(function() {
            // $(".select2").select2();
            $(".select-tags").each(function() {
                $(".select-tags").select2({
                    tags: true,
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: $(this).parent()
                });
            })
            $(".redirect-to").click(function() {
                window.open( $(this).data('href') );
            });
            //   Change Password Script
            let changePasswordValidator = $("#change-password-form").validate({
				rules: {
					"password_confirmation": {
						equalTo: "#password"
					}
				}
			});

			submitForm($("#change-password-form"), {
                formValidator: changePasswordValidator,
                submitBtn: "#change-password-submit",
                complete: function() {
                    $("#change-password-form")[0].reset();
                    submitReset("#change-password-submit");
                },
			});

            //   Change Email Script
            let emailPasswordValidator = $("#change-email-form").validate({
				rules: {
					"email_confirmation": {
						equalTo: "#email"
					}
				}
			});

			submitForm($("#change-email-form"), {
                formValidator: emailPasswordValidator,
				submitBtn: "#change-email-submit",
                complete: function() {
                    $("#change-email-form")[0].reset();
                    submitReset("#change-email-submit");
                },
			});


            // Basic info form
            //   Change Email Script
            let basicFormValidator = $("#basic-info-form").validate({});

			submitForm($("#basic-info-form"), {
                formValidator: basicFormValidator,
				submitBtn: "#basic-info-btn"
			});

            // Institution Info
            let institutionFormValidator = $("#institution-form").validate({});

			submitForm($("#institution-form"), {
                formValidator: institutionFormValidator,
				submitBtn: "#institution-info-btn"
			});


            //  Resend email
            $(".resend-email-verification").click(function() { 
                $("#resend-email-form").submit();
            });
        });

    </script>
@endsection
