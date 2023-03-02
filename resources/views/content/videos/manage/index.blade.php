@extends('layouts/contentLayoutMaster')

@section('title', 'Videos')

@section('vendor-style')

    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
    @include('inc/select2/styles')
@endsection

@section('breadcrumb_right')
    <div class="dropdown">
        <button class="btn-icon btn btn-primary  btn-sm get-content" data-toggle="modal"
            data-target="#dynamic-modal" data-url="{{ route('videos.create') }}" data-title="Add Video"
            type="button" aria-haspopup="true" aria-expanded="false">
            <i data-feather="plus"></i>
        </button>
    </div>
@endsection

@section('page-style')
    
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-none">
                        <h4 class="card-title">Videos</h4>
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
                                    <th>Title</th>
                                    <th>Video</th>
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
    @include('inc/select2/scripts')
@endsection

@section('page-script')
    <script>
        var datatable = $("#datatable").DataTable({

            ajax: {
                url: route('videos.index'),
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
                    data: 'title'
                },
                {
                    data: 'path'
                },
                {
                    data: 'status'
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
                [3, "desc"]
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

        $("body").on("change", "#logo", function() {
            readURL($(this)[0], $("#logo-placeholder"));
        });
        
        function dynamicScript() {
            $(".modal-dialog").addClass('modal-lg');
            initSelectTag();
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

        $('#dynamic-modal').on('hidden.bs.modal', function (e) {
            $(".dynamic-content").html("");
        });

        $("body").on("click", ".view-video", function() {
            let videoLink = $(this).data('href');
            $(".modal-dialog").addClass('modal-lg');
            $("#dynamic-modalTitle").html( $(this).data('title') );
            $(".dynamic-content").html(`
                <video controls class='w-100' src='${videoLink}' />
            `);
        });
    </script>
@endsection
