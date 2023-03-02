@extends('layouts/contentLayoutMaster')

@section('title', 'USERS')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('content')
    <div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('user.update', $user) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Name</label>
                        </x-slot>
                        <input required type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" />
                        @error('name')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>

                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Email</label>
                        </x-slot>
                        <input required type="text" class="form-control" id="email" name="email" value="{{ $user->email }}" />
                        @error('email')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>

                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Mobile No.</label>
                        </x-slot>
                        <input required type="text" class="form-control" id="mobile" name="mobile"  value="{{ $user->mobile }}"/>
                        @error('mobile')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>

                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">Select House</label>
                        </x-slot>
                        <select  id="nameid" name="house_id">
                            @foreach ($societies as $society )
                                <option disabled><b>{{ $society->name }}</b></option>
                                @if($society->houses->first())
                                        @foreach ($society->houses as $house )
                                            @if($user->house_id==$house->id)
                                                <option value="{{ $house->id }}" selected>{{ $house->house_no }}</option>
                                            @else
                                                <option value="{{ $house->id }}">{{ $house->house_no }}</option>
                                            @endif
                                        @endforeach
                                @endif
                            @endforeach
                        </select>
                        @error('society_id')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    </div>
      
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    
    <script type="text/javascript">
    
    $("#nameid").select2({
            placeholder: "Select a Name",
            allowClear: true
        });
    </script>
@endsection

