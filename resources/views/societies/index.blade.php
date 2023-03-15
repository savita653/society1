@extends('layouts/contentLayoutMaster')

@section('title', 'SOCIETIES')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('breadcrumb_right')
    <div class="dropdown">
        <button class="btn-icon btn btn-primary  btn-sm get-content" data-toggle="modal" data-target="#dynamic-modal"
            data-url="{{ route('society.create') }}" data-title="Create Society" type="button" aria-haspopup="true"
            aria-expanded="false">
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
            <th>HOUSES</th>
            <th>ACTION</th>
            <th></th>
        </tr>
        @foreach($societies as $society)
            <tr>
                <td> {{ $society->name }}</td>
                <td> {{ $society->description }}</td>
                @php
                    $path = str_replace('public', 'storage', $society->image);
                @endphp
                @if ($society->image)
                    <td><img src="https://society1.test/{{ $path }}" width="60px" height="60px"></td>
                @else
                    <td>NOT UPLOADED</td>
                @endif
                <td><a href="{{ route('society.house.index', $society) }}" class="btn btn-primary"> HOUSE </a></td>
                <td><button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#editModal{{ $society->id }}">
                    Edit
                </button></td>
                <td>
                    <form method="POST" action="{{ route('society.delete', $society->id) }}">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip"
                            title='Delete'>Delete</button>
                    </form>
                </td>
            </tr>

             {{-- edit user modal --}}
             <script>
                $(document).ready(function() {
                    $('.post_edit').on('click', function() {
                        $('.name_err1').text('')
                        $('.description_err1').text('')
                        
                        var form = $('#postform1').serialize()
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('society.update', $society->id) }}",
                            data: form,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                if ($.isEmptyObject(data.error)) {
                                    alert(data.success)
                                    document.getElementById("postform1").reset();
                                } else {
                                    printErrorMsg(data.error);
                                }
                            }
                        });

                        function printErrorMsg(msg) {
                            $.each(msg, function(key, value) {
                                console.log(key);
                                $('.' + key + '_err1').text(value);
                            });
                        }
                    });
                });
            </script>

            <!--edit  Modal -->
            <div class="modal fade" id="editModal{{ $society->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModal{{ $society->id }}Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-name" id="editModal{{ $society->id }}Label">Edit society</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="postform1" >
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        aria-describedby="emailHelp" value="{{ $society->name }}">
                                </div>
                                <div>
                                    <span class="text-danger error-text name_err1"></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">description</label>
                                    <input type="text" class="form-control" name="description" id="description"
                                        aria-describedby="emailHelp" value="{{ $society->description }}">
                                </div>
                                <div>
                                    <span class="text-danger error-text description_err1"></span>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="post_edit btn btn-primary">Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- end edit modal --}}


        @endforeach
    </table>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
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
