@extends('layouts/contentLayoutMaster')

@section('title', 'HOUSES')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('content')
<div class="row mt-1">
    <div class="col-12">
        <form id="form" method="post" action="{{ route('society.house.update', [$society, $house]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" for="name">House No.</label>
                        </x-slot>
                        <input required type="text" class="form-control" name="house_no"  value="{{ $house->house_no}}" />
                        @error('house_no')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label">ADDRESS</label>
                        </x-slot>
                        <textarea name="address" value= >{{ $house->address}}</textarea>
                        @error('address')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label">CAPACITY</label>
                        </x-slot>
                        <input required type="text" class="form-control" name="capacity"  value="{{ $house->capacity}}"/>
                        @error('capacity')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >OWNER</label>
                        </x-slot>
                        <select name="owner">
                            @foreach($users as $user)
                                @if($user->id == $houseDetails->owner)
                                    <option value="{{ $user->id }}" selected>{{ $user->name}}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('owner')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >RESIDENCE</label>
                        </x-slot>
                        <select name="resident">
                            @foreach($users as $user)
                                @if($user->id == $houseDetails->resident)
                                    <option value="{{ $user->id }}" selected>{{ $user->name}}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('resident')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <x-form-element type='simple'>
                        <x-slot name="label">
                            <label class="form-label" >TOTAL_MEMBER</label>
                        </x-slot>
                        <input type="text" name="total_member" value="{{ $houseDetails->total_member }}">
                        @error('owner')
                            <x-error-message :message="$message" />
                        @enderror
                    </x-form-element>
                </div>
            </div>

            <div class="form-group">
                <button id="submit" type="submit" class="btn btn-primary">UPDATE</button>
            </div>
        </form>
    </div>
</div>
@endsection
