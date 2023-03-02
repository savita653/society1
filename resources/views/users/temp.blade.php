<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

<body>
    <script>
        $(document).ready(function() {
            $('.post_create').on('click', function() {
                $('.name_err').text('')
                $('.email_err').text('')
                $('.password_err').text('')
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
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
      ADD
    </button>

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
                            <label for="exampleInputEmail1">mobile</label>
                            <input type="mobile" class="form-control" name="mobile" id="mobile"
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
</body>

</html>
{{-- 
    

@section('content')
  
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center font-weight-bold">
            </div>
            <div class="card-body">
                <form name="create" id="create" method="post" action="javascript:void(0)">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" id="mobile" name="mobile" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        if ($("#create").length > 0) {
            jQuery("#create").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 50
                    },
                    email: {
                        required: true,
                        maxlength: 50,
                        email: true,
                    },
                    mobile: {
                        required: true,
                        maxlength: 10
                    },
                },
                messages: {
                    name: {
                        required: "Please enter name",
                        maxlength: "Your name maxlength should be 50 characters long."
                    },
                    email: {
                        required: "Please enter valid email",
                        email: "Please enter valid email",
                        maxlength: "The email name should less than or equal to 50 characters",
                    },
                    mobile: {
                        required: "Please enter mobile no.",
                        maxlength: "Your message name maxlength should be 10 characters long."
                    },
                },
                submitHandler: function(form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#submit').html('Please Wait...');
                    $("#submit").attr("disabled", true);
                    $.ajax({
                        url: "{{ route('user.store') }}",
                        type: "POST",
                        data: $('#create').serialize(),
                        success: function(response) {
                            $('#submit').html('Submit');
                            $("#submit").attr("disabled", false);
                            alert('Ajax form has been submitted successfully');
                            document.getElementById("create").reset();
                        }
                    });
                }
            })
        }
    </script>
@endsection --}}
