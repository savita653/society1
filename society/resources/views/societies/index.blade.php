@extends('layouts.main')
@section('content')
    @foreach($societies as $society)
        {{ $society->name }}
        <a href="{{ route('society.edit', $society) }}">edit</a>
        <form action="{{ route('society.delete', $society) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="delete">
        </form>
    @endforeach
@endsection

