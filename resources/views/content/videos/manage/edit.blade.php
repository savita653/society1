<form id="form" method="post" action="{{ route('videos.update', $video->id) }}">
    <div class="row mt-1">
        <div class="col-12 col-md-6">
            @csrf
            @method('PUT')
            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="name">Title</label>
                </x-slot>
                <input required value="{{ $video->title }}" type="text" class="form-control" id="title" name="title" placeholder="Title" />
                @error('title')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>
            
            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="name">Status</label>
                </x-slot>
                <div class="position-relative">
                    <select data-nosearch="1"  name="status" id="status" class="form-control select-tags">
                        @foreach( config('setting.video_status') as $key => $status )
                            <option {{ $video->status == $key ? 'selected' : '' }} value="{{  $key }}">{{  $status }}</option>
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
                            <option {{ in_array($keyword->id, old('keywords', $video->keywords()->pluck('keyword_id')->toArray()) ?? [] ) ? 'selected' : '' }} value="{{ $keyword->id }}">
                                {{ $keyword->keyword_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('keywords')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Update</button>
            </div>
           
        </div>
        <div class="col-12 col-md-6">
            <x-form-element type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="name">Video</label>
                </x-slot>
                <div class="form-group">
                    <video controls class="w-100" src="{{ config('setting.s3_url') . $video->path }}"></video>
                </div>

                @error('video')
                    <x-error-message :message="$message" />
                @enderror
            </x-form-element>

            <div class="form-group">
                <div class="form-group">
                    <label class="control-label" for="logo">Video Thumbnail</label>
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
                <img class="img-fluid event-image rounded" src="{{ $video->getImage() }}" alt="" id="logo-placeholder" />
            </div>
        </div>
        
    </div>
</form>
