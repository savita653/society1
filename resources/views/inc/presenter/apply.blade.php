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
            <label for="login-about_presentation" class="form-label">Briefly, what will your presentation be
                about? </label>
            <textarea style="min-height: 122px;" required type="text"  class="form-control @error('about_presentation') is-invalid @enderror"
                id="login-about_presentation" name="about_presentation"
                placeholder="Briefly, what will your presentation be about?"
                aria-describedby="login-about_presentation" tabindex="1"
                autofocus>{{ old('about_presentation') }}</textarea>
            <small class='d-block'><i>Your privacy is important to us. All information will be kept private, see our <a target="_blank" href='{{  config('setting.privacy_url') }}'>Privacy Policy</a></i></small>
            @error('about_presentation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- sudo chmod 666 /opt/bitnami/apps/wordpress/htdocs/wp-config.php && sudo chmod 666 /opt/bitnami/apps/wordpress/conf/htaccess.conf --}}
        <div class="form-group">
            <label for="login-about_presentation" class="form-label d-block">Has any of the material in your
                presentation already been published?</label>
            <div class="custom-control custom-radio d-inline">
                <input tabindex="1" required {{ old('is_published') ? 'checked' : '' }} value="1" name="is_published"
                    type="radio" class="custom-control-input" id="is_published_yes">
                <label class="custom-control-label" for="is_published_yes">
                    <small> Yes </small>
                </label>
            </div>
            <div class="custom-control custom-radio d-inline">
                <input tabindex="1" required {{ old('is_published') ? 'checked' : '' }} value="0" name="is_published"
                    type="radio" class="custom-control-input" id="is_published_no">
                <label class="custom-control-label" for="is_published_no">
                    <small> No</small>
                </label>
            </div>
            <div class="mt-2 is-published-box {{ old('is_published') ? '' : 'd-none' }}">
                <textarea type="text"
                    {{ old('is_published') ? 'required' : '' }}
                    class="form-control @error('presentation_published_info') is-invalid @enderror"
                    tabindex="1"
                    id="login-presentation_published_info" name="presentation_published_info"
                    placeholder="Please provide the relevant citations / references"
                    aria-describedby="login-presentation_published_info" tabindex="1"
                    autofocus>{{ old('presentation_published_info') }}</textarea>
            </div>
            @error('presentation_published_info')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="login-presentation_keywords" class="form-label">Enter keywords relevant to your presentation {!! Helper::tooltipInfo("Please check all that apply. To specify new keywords, Type your keyword and press enter.") !!}</label>
            <div class="position-relative">
                <select
                    multiple
                    tabindex="1"
                    class="select2 form-control select-tags @error('presentation_keywords') is-invalid @enderror"
                    id="login-presentation_keywords" name="presentation_keywords[]"
                    >
                    @foreach(old('presentation_keywords', json_decode($userMeta['presentation_keywords'] ?? "{}",true) ?? '') as $value)
                        <option selected value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                        <option value="covid">COVID</option>
                        <option value="medical">Medical</option>
                        <option value="pharma">Pharma</option>
                </select>
            </div>
            @error('presentation_keywords')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>





<script>
    function applyPresenterScript() {
        $(".select-tags").each(function() {
            $(".select-tags").select2({
                tags: true,
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $(this).parent()
            });
        })

        $("input[name='is_published']").on('change', function() {
            if ($(this).val() == 1) {
                $(".is-published-box").removeClass('d-none');
                $(".is-published-box").find('textarea').attr("required", "required");
            } else {
                $(".is-published-box").addClass('d-none');
                $(".is-published-box").find('textarea').removeAttr("required");
            }
        });
    }
</script>