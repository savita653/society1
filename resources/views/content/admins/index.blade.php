@extends('layouts/contentLayoutMaster')

@section('title', 'Admins')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
@endsection


@section('breadcrumb_right')
    <div class="dropdown">
        <button class="btn-icon btn btn-primary  btn-sm get-content" data-toggle="modal"
            data-target="#dynamic-modal" data-url="{{ route('admins.create') }}" data-title="Create Admin User"
            type="button" aria-haspopup="true" aria-expanded="false">
            <i data-feather="plus"></i>
        </button>
    </div>
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-none">
                        <h4 class="card-title">Admins</h4>
                    </div>
                    <div class="card-datatable">
                        <input type="hidden" name="view" value="all">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a data-view="all" class="nav-link change-view active" id="all-tab" data-toggle="tab"
                                    aria-controls="all" role="all" aria-selected="true">All</a>
                            </li>
                            <li class="nav-item">
                                <a data-view="trash" class="nav-link change-view" id="trash-tab" data-toggle="tab"
                                    aria-controls="trash" role="tab" aria-selected="true">Trash</a>
                            </li>
                        </ul>
                        <table id="datatable" class="dt-responsive table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    @include('inc/datatable/scripts')
    @include('inc/form/scripts')
    @include('inc/sweet-alert/scripts')
@endsection

@section('page-script')
    <script>
        var datatable = $("#datatable").DataTable({
            ajax: {
                url: route('admins.index'),
                data: function(d) {
                    d.view = $("input[name='view']").val();
                },
                error: function(data) {
                    let json = data.responseJSON;
                    if(data.status == 401) {
                        setAlert({
                            code: "error",
                            title: "Oops!",
                            message: "Unauthenticated.",
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        setAlert({
                            code: "error",
                            title: "Oops!",
                            message: "Something went wrong.",
                        });
                    }
                    console.warn(json.message);
                },
            },
            columns: [{
                    data: 'name'
                },
                {
                    data: 'last_name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'is_active'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'action'
                },
            ],
            drawCallback: function() {
                $("*[data-toggle='tooltip']").tooltip();
            },
            "order": [
                [4, "desc"]
            ],
            // 'createdRow': function (row, data, dataIndex) {
            // },
        });

        // Delete Record
        $("body").on("click", ".delete-record", function(e) {
            let btn = $(this);

            confirm({
                confirmButtonText: btn.data("title"),
                text: ""
            }, {
                yes: function() {
                    submitAjax({
                        type: "DELETE",
                        url: btn.data('url'),
                        dataType: "json",
                    }, {
                        submitBtn: btn
                    });
                }
            });
        });

        // Restore Record
        $("body").on("click", ".restore-record", function(e) {
            let btn = $(this);
            confirm({
                confirmButtonText: btn.data("title"),
                text: ""
            }, {
                yes: function() {
                    submitAjax({
                        type: "GET",
                        url: btn.data('url'),
                        dataType: "json",
                    }, {
                        submitBtn: btn
                    });
                }
            });
        });

        $("body").on("keyup", ".edit-password", function(e) {
            if ($(".edit-password").val() != "") {
                $(".email-cred").removeClass("d-none");
            } else {
                $(".email-cred").addClass("d-none");
            }
        });

        // Change View
        $("body").on("click", ".change-view", function() {
            $("input[name='view']").val($(this).data('view'));
            datatable.draw();
        });

        function dynamicScript() {
            let formValidator = $("#form").validate({
                // rules: {
                //     email: {
                //         remote: route('api.email.unique')
                //     }
                // },
                // message: {
                //     email: {
                //         remote: "Email address has already been taken."
                //     }
                // }
            });
            submitForm($("#form"), {
                formValidator: formValidator,
                submitBtn: "#submit",
            });
        }

    </script>
@endsection
