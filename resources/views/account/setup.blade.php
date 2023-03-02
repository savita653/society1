@extends('layouts/fullLayoutMaster')

@section('title', 'Setup Account')

@section('vendor-style')
  <!-- vendor css files -->
  @include('inc/select2/styles')

@endsection

@section('page-style')
    {{-- Page Css files --}}
    @include('inc/form/styles')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-auth.css')) }}">
    <style>
        .auth-inner-extend {
            max-width: 1024px!important;
        }
    </style>
@endsection

@section('content')
    <div class="auth-wrapper auth-v1 px-2">
        <div class="auth-inner auth-inner-extend py-2 ">
            <!-- Login v1 -->
            <div class="card mb-0">
                <div class="card-body">
                    <x-logo />

                    <div id="subscription-button" class="d-none">
                        
                    </div>
                    
                    <form id="setup-form" class="mt-2" method="POST" action="{{ route('subscriber.setup') }}">
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
                                <label>Institution Address</label>
        
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
        
                                <div class="row">
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
                                </div>
        
                                <div class="row">
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
        
                                <div class="form-group">
                                    <label for="login-hear_about_us" class="form-label">How did you hear about
                                        {{ config('app.name') }}?</label>
                                    <div class="position-relative">
                                        <select required
                                            class="select2 form-control  @error('hear_about_us') is-invalid @enderror"
                                            id="login-hear_about_us" name="hear_about_us"
                                            placeholder="How did you hear about {{ config('app.name') }}?"
                                            value="{{ old('hear_about_us', $userMeta['hear_about_us'] ?? "") }}">
                                            <option value="">--Select--</option>
                                            @foreach (config('setting.how_did_you_hear_options') as $value)
                                                <option 
                                                    {{ old('hear_about_us', $userMeta['hear_about_us'] ?? "") == $value ? "selected" : "" }}
                                                    value="{{ $value }}">{{ $value }}</option>
                                            @endforeach
                                            <option data-text="1" value="other">Other</option>
                                        </select>
                                    </div>
                                    @error('hear_about_us')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
        
                                <div class="form-group membership-level-box-1">
                                    <label>My membership level is:</label>
                                    <select required name="membership_level_1" class="form-control membership-level-1">
                                        <option value="">--Select--</option>
                                        @foreach ($membershipLevels as $membershipLevel)
                                            <option value="{{ $membershipLevel->id }}">{{ $membershipLevel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
        
                                <div class="form-group membership-level-box-2 d-none">
                                    <select  name="membership_level_2" class="form-control membership-level-2">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
        
                                <div class="form-group membership-level-box-3 d-none">
                                    <select  name="membership_level_3" class="form-control membership-level-3">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
        
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                    <input {{ old('email_notification') ? 'checked' : '' }} name="email_notification" type="checkbox" class="custom-control-input" id="email_notification" >
                                    <label class="custom-control-label" for="email_notification">
                                        <small>
                                        Would you like to receive email notifications of upcoming events?</small>
                                    </label>
                                    </div>
                                </div>
                        
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                    <input {{ old('can_contact') ? 'checked' : '' }} name="can_contact" type="checkbox" class="custom-control-input" id="can_contact" >
                                    <label class="custom-control-label" for="can_contact">
                                        <small>
                                        Would you like to be contacted by interested recruiters? </small>
                                    </label>
                                    </div>
                                </div>
                        
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                    <input {{ old('newsletter') ? 'checked' : '' }} name="newsletter" type="checkbox" class="custom-control-input" id="newsletter" >
                                    <label class="custom-control-label" for="newsletter">
                                        <small>
                                        Subscribe to our newsletter to get latest news and updates.</small>
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-3 col-12">
                                <button id="submit-btn" type="submit" class="btn btn-primary btn-block"
                                    tabindex="4">
                                    Continue
                                </button>
                            </div>
                        </div>
                        @role('presenter')
                            <div class="row justify-content-center mt-1">
                                <div class="col-md-3 col-12 text-center">
                                    <p><a href="{{ route('presenter.events.index') }}">Back to Dashboard</a></p>
                                </div>
                            </div>
                        @endrole
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
  <!-- vendor css files -->
  @include('inc/select2/scripts')

@endsection

@section('page-script')
    
    @include('inc/form/scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('cashier.key') }}')
    </script>

    <script>
        $(document).ready(function() {

            //  Resend email
            $(".resend-email-verification").click(function() { 
                $("#resend-email-form").submit();
            });

            $(".select-tags").each(function() {
                $(".select-tags").select2({
                    tags: true,
                    dropdownAutoWidth: true,
                    width: '100%',
                    dropdownParent: $(this).parent()
                });
            })

            // Setup form validation & submission
            let setupFormValidator = $("#setup-form").validate({});
            submitForm($("#setup-form"), {
                formValidator: setupFormValidator,
                submitBtn: "#submit-btn",
                beforeSubmit: function() {
                    submitLoader("#submit-btn");
                    $("#subscription-button").html("");
                },
                success: function(response) {
                    setAlert(response);
                    $("#subscription-button").html(response);
                    $("#subscription-button").find("button").click();
                }
            });

            // Membership level events
            $(".membership-level-1").on("change", function() {
                handleMembershipChange($(this), 2);
            });
            $(".membership-level-2").on("change", function() {
                handleMembershipChange($(this), 3);
            });
           
            $(".membership-level-3,.membership-level-2, .membership-level-1").on("change", function() {
                $(".other-text-input").next().remove();
                $(".other-text-input").remove();
                if($(this).find("option:selected").attr("data-text") == 1) {
                    $(this).after(`<input type='text' name='membership_input_${$(this).val()}' class='form-control other-text-input mt-50' required placeholder='Please specify...' />`);
                }
            });

            $("select[name='hear_about_us']").change(function() {
                $(".other-hear-input").remove();
                $("#hear_us_other-error").remove();
                if($(this).find('option:selected').attr('data-text') == 1) {
                    $(this).after(`<input type='text' name='hear_us_other' class='form-control other-hear-input mt-50' required placeholder='Please specify...' />`);
                }
            });

            
        });

        function handleMembershipChange($instance, level) {
            let box = $(`.membership-level-box-${level}`);    
              

            $.ajax({
                url: route('membership-level', {
                    parentId: $instance.val()
                }),
                beforeSend: function() {
                    $(`.membership-level-box-${level}`).addClass('d-none');
                    $(`.membership-level-box-${level + 1}`).addClass('d-none');
                    for(let i = 1; i <= 3; i++) {
                        $(`.membership-level-${level}`).removeAttr('required');
                        $(`.membership-level-${level}`).val('');
                    }
                    for(let i = level; i <= 3; i++) {
                        $(`.membership-level-${i}`).html(`<option value=''>--Select--</option>`);
                    }
                },
                success: function(response) {
                    if (response.length > 0) {
                        $(".other-text-input").remove();
                        // Append 2nd level of membership
                        box.removeClass('d-none');
                        $(`.membership-level-${level}`).html(`<option value=''>--Select--</option>`);
                        $(`.membership-level-${level}`).attr('required', 'required');
                        response.forEach(function(value, index) {
                            $(`.membership-level-${level}`).append(
                                `<option data-text='${value.required_textbox}' value='${value.id}'>${value.name}</option>`);
                        });
                    } else {
                        $(`.membership-level-${level}`).removeAttr('required');
                        box.addClass('d-none');
                    }
                }
            });
        }

    </script>
@endsection
