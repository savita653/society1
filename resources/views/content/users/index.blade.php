@extends('layouts/contentLayoutMaster')

@section('title', 'Subscribers')

@section('vendor-style')
    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
    @include('inc/select2/styles')

@endsection

@section('breadcrumb_right')
    <button data-toggle="collapse" data-target="#filter-box" class="btn btn-primary"><i data-feather='filter'></i> Filters</button>
    <button class="btn btn-primary" id="export-btn"><i data-feather='download'></i> Export</button>
@endsection

@section('content')
    <section id="responsive-datatable">
        <div id="filter-box" class="row collapse">
            <div class="col-md-3 col-12 d-none">
                <div class="form-group">
                    <label for="subscription_status">Subscription Status</label>
                    <div class="position-relative">
                        <select name="subscription_status" id="subscription_status" class="form-control select-tags">
                            <option value="">All</option>
                            <option value="subscribed">Subscribed</option>
                            <option value="not_subscribed">Not Subscribed</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label for="can_contact">Can Contact {!! Helper::tooltipInfo("Q. Would you like to be contacted by interested recruiters?") !!}</label>
                    <div class="position-relative">
                        <select name="can_contact" id="can_contact" class="form-control select-tags">
                            <option value="">All</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-none">
                        <h4 class="card-title">Subscribers</h4>
                    </div>
                    <div class="card-datatable">
                        <input type="hidden" name="view" value="all">
                        {{-- Navbar --}}
                        <table id="datatable" class="dt-responsive table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Can Contact {!! Helper::tooltipInfo("Q. Would you like to be contacted by interested recruiters?") !!}</th>
                                    {{-- <th>Subscription Status</th> --}}
                                    <th>Joined at</th>
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
                url: route('users.index'),
                data: function(d) {
                    d.view = $("input[name='view']").val();
                    d.subscription_status = $("#subscription_status").find("option:selected").val();
                    d.can_contact = $("#can_contact").find("option:selected").val();
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
            buttons: [
                {
                    extend: 'csv',
                    action: newExportAction,
                    exportOptions: {
                        columns: [ 0,1,2,3,4 ]
                    }
                },
                {
                    extend: 'excel',
                    action: newExportAction,
                },
            ],
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
                    data: 'can_contact'
                },
                // {
                //     data: 'subscription_status',
                //     // searchable: false,
                //     // sortable: false
                // },
                {
                    data: 'created_at'
                },
                {
                    data: 'action'
                },
            ],
            "order": [
                [4, "desc"]
            ],
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

        // Filters
        $("body").on("change", "#subscription_status, #can_contact", function() {
            datatable.draw();
        });
        
        // Export
        $("body").on("click", "#export-btn", function() {
            datatable.button(0).trigger();
        });

        function dynamicScript() {
            let formValidator = $("#form").validate({
            });
            submitForm($("#form"), {
                formValidator: formValidator,
                submitBtn: "#submit",
            });
        }

    </script>
@endsection
