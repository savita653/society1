<form id="form" method="post" enctype="multipart/form-data" action="{{ route('events.update', $record->id) }}">
    <div class="row mt-1">
        <div class="col-12 col-md-6">
            @csrf
            @method('PUT')

            @role('super_admin')
                <x-form-element type='simple'>
                    <x-slot name="label">
                        <label class="form-label" for="user_id">Admin of Event</label>
                    </x-slot>
                    <div class="position-relative">
                        <select required aria-describedby="user_id" type="text" class="form-control select-tags" id="user_id" name="user_id">
                            <option {{ $record->user_id == auth()->user()->id ? 'selected' : ''}} value="{{ auth()->user()->id }}">Me</option>
                            @foreach($admins as $admin)
                                <option {{ $record->user_id == $admin->id ? 'selected' : ''}} value="{{ $admin->id }}">{{ $admin->fullName() . " - " . $admin->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id')
                        <x-error-message :message="$message" />
                    @enderror
                </x-form-element>
                <small class="d-none"><strong>Channel Name:</strong> {{  $record->channel_name }}</small>
            @endrole

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="title">Title</label>
                </x-slot>
                <input required type="text" value="{{ old('title', $record->title) }}" class="form-control" id="title" name="title" placeholder="Title" />
                @error('title')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="start_date_time">Start Date & Time</label>
                </x-slot>
                <input required autocomplete="chrome-off" value="{{ old('start_date_time', $record->start_date_time) }}" type="text" class="form-control date-time-picker" id="start_date_time" name="start_date_time"
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
                    <select required aria-describedby="status" type="text" data-nosearch="1" class="form-control select-tags" id="status" name="status">
                        @foreach(config('setting.event_status') as $key => $value)
                            <option {{ old('status', $record->status) == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
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
                            <option 
                                {{ 
                                    in_array(
                                        $keyword->id, 
                                        old(
                                            'keywords', 
                                            $record->keywords()->pluck('keyword_id')->toArray()
                                        ) ?? [] ) ? 'selected' : '' 
                                }} 
                                value="{{ $keyword->id }}">
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
                    <select multiple aria-describedby="users" data-nosearch="1" type="text" class="form-control select-tags" id="users" name="users[]">
                        <optgroup label="Admins">
                            @foreach($admins as $admin)
                                <option {{ in_array($admin->id, old('users', $record->users()->pluck('user_id')->toArray()) ) ? 'selected' : '' }} value="{{ $admin->id }}">{{ $admin->fullName() . " - " . $admin->email }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Presenters">
                            @foreach($presenters as $presenter)
                                <option {{ in_array($presenter->id, old('users', $record->users()->pluck('user_id')->toArray()) ) ? 'selected' : '' }} value="{{ $presenter->id }}">{{ $presenter->fullName() . " - " . $presenter->email }}</option>
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
                <button id="submit" type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
        <div class="col-md-6 col-12">
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
                <img class="img-fluid event-image rounded" src="{{ $record->getImage() }}" alt="" id="logo-placeholder" />
            </div>
        </div>
    </div>
</form>
