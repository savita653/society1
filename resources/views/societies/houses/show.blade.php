@extends('layouts/contentLayoutMaster')

@section('title', 'HOUSES')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('content')
    <table class="table table-info table-hover">
        <tr>
        <th>HOUSE NO.</th>
        <th>ADDRESS</th>
        <th>CAPACITY</th>
        <th>IMAGE</th>
        <th>SOCEITY </th>
        <th>OWNER</th>
        <th>RESIDENT</th>
        </tr>
        @foreach($houses as $house)
        <tr>
            <td> {{ $house->house_no }}</td>
            <td> {{ $house->address }}</td>
            <td> {{ $house->capacity }}</td>
            @php
                $path = str_replace('public', 'storage', $house->image);
            @endphp
            @if($house->image)
                <td><img src="https://society1.test/{{ $path}}" width="60px" height="60px"></td>
            @else
                <td>NOT UPLOADED</td>
            @endif            
            <td>{{ $house->society->name }}</td>
            <td>{{ $house->owners->name }}</td>
            <td>{{ $house->residents->name }}</td>
        </tr>
        
        @endforeach 
  </table>  
  <td><a href="{{ route('society.index') }}" class="btn btn-primary"> BACK </a></td>
@endsection