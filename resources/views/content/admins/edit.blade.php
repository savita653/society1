<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('admins.update', $user->id) }}">
          @csrf
          @method('PUT')
          <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">First Name</label>
                        </x-slot>
                        <input value="{{ old('name', $user->name) }}" required type="text" class="form-control" id="name" name="name" placeholder="Name" />
                        @error('name')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Last Name</label>
                        </x-slot>
                        <input value="{{ old('name', $user->last_name) }}" required type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" />
                        @error('last_name')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

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
            </x-form-element>

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
            <div class="form-group email-cred d-none">
                <div class="custom-control custom-checkbox">
                    <input name='notify' type="checkbox" class="custom-control-input" id="customCheck1" checked>
                    <label class="custom-control-label" for="customCheck1">Email Credentials?</label>
                    {!! Helper::tooltipInfo("If checked, System will send the email & password to above specified email address.") !!}
                </div>
            </div>
            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

