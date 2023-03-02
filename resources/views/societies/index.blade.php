@extends('layouts/contentLayoutMaster')

@section('title', 'SOCIETIES')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('breadcrumb_right')
    <div class="dropdown">
        <button class="btn-icon btn btn-primary  btn-sm get-content" data-toggle="modal"
            data-target="#dynamic-modal" data-url="{{ route('society.create') }}" data-title="Create Society"
            type="button" aria-haspopup="true" aria-expanded="false">
            <b data-feather="plus">+</b>
        </button>
    </div>
@endsection

@section('content')
<table class="table table-info table-hover">
    <tr>
    <th>NAME</th>
    <th>DESCRIPTION</th>
    <th>IMAGE</th>
    <th>ACTION</th>
    <th></th>
    <th></th>
    </tr>
        @foreach($societies as $society)
        <tr>
            <td> {{ $society->name }}</td>
            <td> {{ $society->description }}</td>
            @php
                $path = str_replace('public', 'storage', $society->image);
            @endphp
            @if($society->image)
            <td><img src="https://society1.test/{{ $path}}" width="60px" height="60px"></td>
            @else
                <td>NOT UPLOADED</td>
            @endif
            <td><a href="{{ route('society.edit' , $society) }}" class="btn btn-primary"> EDIT </a></td>
            <td><a href="{{ route('society.house.index' , $society) }}" class="btn btn-primary"> HOUSE </a></td>
            <td>
                <form method="POST" action="{{ route('society.delete', $society->id) }}">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
</table>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this record?`,
              text: "If you delete this, it will be gone forever.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>
@endsection
