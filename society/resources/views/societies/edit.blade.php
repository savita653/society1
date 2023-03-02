@extends('layouts.main')
@section('content')
<form action="{{ route('society.update' ,$society) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>NAME</label>
    <input type="text" name="name" value="{{ $society->name }}">
    <label>DESCRIPTION</label>
    <textarea name="description" value =>{{ $society->description }}</textarea>
    {{-- <label>IMAGE</label>
    <input type="file" name="image"> --}}
    @if($society->parent_id)
        <label>SELECT SOCIETY</label>
        @foreach($societies as $soc)
            @if($soc->id == $society->parent_id)
                <input type="radio" name="parent_id" value="{{ $soc->id }}" checked >
                <label>{{ $soc->name }}</label>
            @else
                <input type="radio" name="parent_id" value="{{ $soc->id }}">
                <label>{{ $soc->name }}</label>
            @endif
        @endforeach
        </select>
    @endif
    <input type="submit" value="SAVE">
</form>
@endsection
