@extends('layouts.main')
@section('content')
    <form action="{{ route('users.store') }}" method="POST" >
        @csrf
        <label>NAME</label>
        <input type="text" name="name">
        @error('name')
            {{ $message }}
        @enderror

        <label>MOBILE</label>
        <input type="text" name="mobile">
        @error('mobile')
        {{ $message }}
        @enderror

        <label>EMAIL</label>
        <input type="email" name="email">
        @error('email')
        {{ $message }}
        @enderror

        <label>PASSWORD</label>
        <input type="password" name="password">
        @error('password')
        {{ $message }}
        @enderror

        <label>SELECT HOUSE</label>
        @foreach($houses as $house)
            <input type="radio" name="house_no" value="{{ $house->id }}">
            <label>{{ $house->name }}</label>
        @endforeach
        @error('house_no')
        {{ $message }}
        @enderror

        <label>OWNER</label>
        <input type="radio" name="isOwner" value="1">
        <label>TENANT</label>
        <input type="radio" name="isOwner" value="0">
        @error('isOwner')
        {{ $message }}
        @enderror

        <label>DOA</label>
        <input type="date" name="DOA">
        @error('DOA')
        {{ $message }}
        @enderror

        <label>DOD</label>
        <input type="date" name="DOD">
        @error('DOD')
        {{ $message }}
        @enderror

        <input type="submit" value="SAVE">
    </form>
@endsection
