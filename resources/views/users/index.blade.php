@extends('layouts/contentLayoutMaster')

@section('title', 'USERS')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('breadcrumb_right')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        ADD
    </button>
@endsection

@section('content')

    {{-- create user modal --}}
    <script>
        $(document).ready(function() {
            $('.post_create').on('click', function() {
                $('.name_err').text('')
                $('.email_err').text('')
                $('.password_err').text('')
                $('.confirmPassword_err').text('')
                $('.mobile_err').text('')

                var form = $('#postform').serialize()
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.store') }}",
                    data: form,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if ($.isEmptyObject(data.error)) {
                            alert(data.success)
                            document.getElementById("postform").reset();
                        } else {
                            printErrorMsg(data.error);
                        }
                    }
                });

                function printErrorMsg(msg) {
                    $.each(msg, function(key, value) {
                        console.log(key);
                        $('.' + key + '_err').text(value);
                    });
                }
            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-name" id="exampleModalLabel">Create User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="postform">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                aria-describedby="emailHelp" placeholder="Enter name">
                        </div>
                        <div>
                            <span class="text-danger error-text name_err"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div>
                            <span class="text-danger error-text email_err"></span>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                aria-describedby="emailHelp" placeholder="Enter password">
                        </div>
                        <div>
                            <span class="text-danger error-text password_err"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Confirm password</label>
                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword"
                                aria-describedby="emailHelp" placeholder="Enter password">
                        </div>
                        <div>
                            <span class="text-danger error-text confirmPassword_err"></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">mobile</label>
                            <input type="text" class="form-control" name="mobile" id="mobile"
                                aria-describedby="emailHelp" placeholder="Enter mobile">
                        </div>
                        <div>
                            <span class="text-danger error-text mobile_err"></span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="post_create btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- user listing --}}
    <table class="table table-info table-hover">
        <tr>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>MOBILE</th>
            <th>ACTION</th>
            <th></th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td> {{ $user->name }}</td>
                <td> {{ $user->email }}</td>
                <td> {{ $user->mobile }}</td>
                <td><button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#editModal{{ $user->id }}">
                        Edit
                    </button>
                </td>
                <td>
                    <form method="POST" action="{{ route('user.delete', $user->id) }}">
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
                        $('.email_err1').text('')
                        $('.mobile_err1').text('')

                        var form = $('#postform1').serialize()
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('user.update', $user->id) }}",
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
            <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog"
                aria-labelledby="editModal{{ $user->id }}Label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-name" id="editModal{{ $user->id }}Label">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" id="postform1">
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        aria-describedby="emailHelp" value="{{ $user->name }}">
                                </div>
                                <div>
                                    <span class="text-danger error-text name_err1"></span>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        aria-describedby="emailHelp" value="{{ $user->email }}">
                                </div>
                                <div>
                                    <span class="text-danger error-text email_err1"></span>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">mobile</label>
                                    <input type="textbox" class="form-control" name="mobile" id="mobile"
                                        aria-describedby="emailHelp" value="{{ $user->mobile }}">
                                </div>
                                <div>
                                    <span class="text-danger error-text mobile_err1"></span>
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

    {{-- delete confirmation popup --}}

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
