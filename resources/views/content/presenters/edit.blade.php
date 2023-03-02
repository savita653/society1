<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('presenters.update', $user->id) }}">
          @csrf
          @method('PUT')
            {{-- <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="name">Name</label>
                </x-slot>
                <input required type="text" value="{{ old('name', $user->name) }}" class="form-control" id="name" name="name" placeholder="Name" />
                @error('name')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="email">Email</label>
                </x-slot>
                <input required value="{{ old('email', $user->email) }}" autocomplete="chrome-off" type="email" class="form-control" id="email"
                    name="email" placeholder="Email" />
                @error('email')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="password">Password</label>
                </x-slot>
                <input type="password" class="form-control edit-password" id="password" name="password"
                    placeholder="Password" />
                @error('password')
                    <x-error-message :message="$message" />
                @enderror
                <small class='d-block'>Leave blank to use existing password</small>
            </x-form-element> --}}

            <div class="col-12 col-md-4">
                <div class=" form-group email-cred d-none">
                    <div class="custom-control custom-checkbox">
                        <input name='notify' type="checkbox" class="custom-control-input" id="customCheck1" checked>
                        <label class="custom-control-label" for="customCheck1">Email Credentials?</label>
                        {!! Helper::tooltipInfo("If checked, System will send the email & password to above specified email address.") !!}
                    </div>
                </div>
            </div>
            @if($user->approved())
            <div class="row align-items-end">
                <div class="col-12 col-md-4">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="password">Account Status {!! Helper::tooltipInfo("Inactive Account cannot login in to their dashboard.") !!}</label>
                        </x-slot>
                        <select name="is_active" class="form-control" id="is_active">
                            @foreach(config('setting.user_status') as $key => $value)
                                <option {{ old('is_active', $user->is_active) == $key ? 'selected' : '' }} value="{{ $key }}">{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                    </x-form-element>
                </div>
                
                <div class="form-group">
                    <button id="submit" type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            @else
            <div class="row align-items-end">
                <div class="col-12 col-md-4">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="profile_status">Profile Status</label>
                        </x-slot>
                        <select name="profile_status" class="form-control" id="profile_status">
                            <option value="">--Select--</option>
                            @foreach(config('setting.profile_status') as $key => $value)
                                <option {{ old('profile_status', $user->profile_status) == $key ? 'selected' : '' }} value="{{ $key }}">{{ ucfirst($value) }}</option>
                            @endforeach
                        </select>
                    </x-form-element>
                </div>
                
                <div class="form-group">
                    <button id="submit" type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h4>Presenter Submission</h4>
        <p>
            <strong>Name:</strong> {{ $user->fullName() }}
        </p>
        <p>
            <strong>Email:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </p>
        <p>
            <strong>Institution Name:</strong> {{ $userMeta['institution_name'] ?? "N/A" }}
        </p>
        <p>
            <strong>Department:</strong> {{ $userMeta['department'] ?? "N/A" }}
        </p>
        <p>
            <strong>Institution Address:</strong>
            <div class="row">
                <div class="col-12">
                    Street Address: {{ $userAddress['street_name'] ?? "N/A" }}
                </div>
                <div class="col-6">City: {{ $userAddress['city'] ?? "N/A" }}</div>
                <div class="col-6">State: {{ $userAddress['State'] ?? "N/A" }}</div>
                <div class="col-6">Postal Code: {{ $userAddress['postal_code'] ?? "N/A" }}</div>
                <div class="col-6">Country: {{ $userAddress['country'] ?? "N/A" }}</div>
            </div>
        </p>
        <p>
            <strong>Briefly, what will your presentation be about?</strong>
            <div>
                {{ $userMeta['about_presentation'] ?? "N/A" }}
            </div>
        </p>
        <p>
            <strong>Has any of the material in your presentation already been published?</strong>
            <div>
                {{ $userMeta['presentation_published_info'] ?? "No" }}
            </div>
        </p>
        <p>
            <strong>Keywords relevant to your presentation</strong>
            <div>
                @forelse(Helper::jsonToArray($userMeta['presentation_keywords'] ?? "{}") as $value)
                    <span class="badge badge-primary">{{ $value }}</span>
                @empty
                    N/A
                @endforelse
            </div>
        </p>
    </div>
</div>

