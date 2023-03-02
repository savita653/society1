<form id="form" method="post" enctype="multipart/form-data" action="{{ route('events.store') }}">
    <div class="row mt-1">
        
        <div class="col-12 col-md-6">
            @csrf
            @role('super_admin')
                <x-form-element type='simple'>
                    <x-slot name="label">
                        <label class="form-label" for="user_id">Admin of Event</label>
                    </x-slot>
                    <div class="position-relative">
                        <select required aria-describedby="user_id" type="text" class="form-control select-tags" id="user_id" name="user_id">
                            <option value="{{ auth()->user()->id }}">Me</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->fullName() . " - " . $admin->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id')
                        <x-error-message :message="$message" />
                    @enderror
                </x-form-element>
            @endrole
            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="title">Title</label>
                </x-slot>
                <input required maxlength="255" type="text" class="form-control" id="title" name="title" placeholder="Title" />
                @error('title')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="start_date_time">Start Date & Time</label>
                </x-slot>
                <input required autocomplete="chrome-off" type="text" class="form-control date-time-picker" id="start_date_time" name="start_date_time"
                    placeholder="Start Date & Time" />
                @error('start_date_time')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="status">Status</label>
                </x-slot>
                <div class="position-relative">
                    <select required aria-describedby="status" data-nosearch="1" type="text" class="form-control select-tags" id="status" name="status">
                        @foreach(config('setting.event_status') as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @error('status')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="keywords">Keywords {!! Helper::tooltipInfo("Please check all that apply.") !!}</label>
                </x-slot>
                <div class="position-relative">
                    <select required multiple aria-describedby="keywords" data-nosearch="1" data-tags="1" type="text" class="form-control select-tags" id="keywords" name="keywords[]">
                        @foreach($keywords as $keyword)
                            <option {{ in_array($keyword->id, old('keywords') ?? [] ) ? 'selected' : '' }} value="{{ $keyword->id }}">
                                {{ $keyword->keyword_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('keywords')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="presenters">Admins / Presenters {!! Helper::tooltipInfo("Please check all that will be part of the Event.") !!}</label>
                </x-slot>
                <div class="position-relative">
                    <select multiple aria-describedby="presenters" data-nosearch="1" type="text" class="form-control select-tags" id="users" name="users[]">
                        <optgroup label="Admins">
                            @foreach($admins as $admin)
                                <option {{ in_array($admin, old('users') ?? [] ) ? 'selected' : '' }} value="{{ $admin->id }}">{{ $admin->fullName() . " - " . $admin->email }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Presenters">
                            @foreach($presenters as $presenter)
                                <option {{ in_array($presenter, old('users') ?? [] ) ? 'selected' : '' }} value="{{ $presenter->id }}">{{ $presenter->fullName() . " - " . $presenter->email }}</option>
                            @endforeach
                        </optgroup>

                    </select>
                </div>

                <small>Want to invite other presenters? 
                    <a type='button'
                        data-copy="{{ route('become-a-presenter') }}"
                        class='copy-me' 
                        href="#"
                        >
                            Copy Registration Form Link
                    </a>
                </small>

                @error('presenters')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Create</button>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="control-label" for="logo">Event Image</label>
                    <div class=" custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logo" data-filename-placement="inside" />
                        <label class="custom-file-label" for="inputGroupFile01">Select an image</label>
                    </div>
                    <small>Only .jpg, .jpeg, .png file types are allowed.</small>
                </div>
            </div>
            @error('logo')
                <x-error-message :message="$message" />
            @enderror

            <div class="">
                <img class="img-fluid event-image rounded" src="" alt="" id="logo-placeholder" />
            </div>
        </div>
    </div>
</form>
