@extends('layouts/contentLayoutMaster')

@section('title', 'SOCIETIES')

@section('vendor-style')
    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('society.update' ,$society) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>NAME</label>
        <input type="text" name="name" value="{{ $society->name }}">
        <label>DESCRIPTION</label>
        <textarea name="description" value =>{{ $society->description }}</textarea>
        <input type="submit" value="SAVE" class="btn btn-primary">
    </form>
</div>
@endsection

