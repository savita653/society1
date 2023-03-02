<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('admins.store') }}">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">First Name</label>
                        </x-slot>
                        <input required type="text" class="form-control" id="name" name="name" placeholder="Name" />
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
                        <input required type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" />
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
                <input required autocomplete="chrome-off" type="email" class="form-control" id="email" name="email"
                    placeholder="Email" />
                @error('email')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="password">Password</label>
                </x-slot>
                <input required type="password" class="form-control" autocomplete="new-password" id="password" name="password"
                    placeholder="Password" />
                @error('password')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input name="notify" type="checkbox" class="custom-control-input" id="customCheck1" checked>
                    <label class="custom-control-label" for="customCheck1">Email Credentials?</label>
                    {!! Helper::tooltipInfo("If checked, System will send the email & password to above specified email address.") !!}
                </div>
            </div>
            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
