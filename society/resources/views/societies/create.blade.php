@extends('layouts.main')
@section('content')
    <form action="{{ route('society.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>NAME</label>
        <input type="text" name="name">
        <label>DESCRIPTION</label>
        <textarea name="description"></textarea>
        <label>IMAGE</label>
        <input type="file" name="image">
        <label>SELECT SOCIETY</label>
        @foreach($societies as $society)
            <input type="radio" name="parent_id" value="{{ $society->id }}">
            <label>{{ $society->name }}</label>
        @endforeach
        <input type="submit" value="SAVE">
    </form>
@endsection
