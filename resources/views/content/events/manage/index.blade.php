@extends('layouts/contentLayoutMaster')

@section('title', 'Events')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
    @include('inc/select2/styles')

     <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <style>
        .modal .modal-dialog-aside {
            width: 50%!important;
        }
    </style>
@endsection

@section('breadcrumb_right')
    <div class="dropdown">
    <button data-toggle="collapse" data-target="#filter-box" class="btn btn-primary"><i data-feather='filter'></i> Filters</button>

        <button data-toggle='tooltip' title='Calendar View' class="btn-icon btn btn-primary  btn-sm calendar-view-btn"
            type="button" aria-haspopup="true" aria-expanded="false">
            <i data-feather="calendar"></i>
        </button>
        <button data-toggle='tooltip' title='List View' class="btn-icon btn btn-primary  btn-sm list-view-btn d-none"
            type="button" aria-haspopup="true" aria-expanded="false">
            <i data-feather="list"></i>
        </button>
        <button class="btn-icon btn btn-primary  btn-sm get-content" data-toggle="modal"
            data-target="#dynamic-modal" data-url="{{ route('events.create') }}" data-title="Create Event"
            type="button" aria-haspopup="true" aria-expanded="false">
            <i data-feather="plus"></i>
        </button>
    </div>
    
@endsection

@section('content')

<section class='calendar-view d-none'>
    <div class="app-calendar overflow-hidden border">
      <div class="row no-gutters">
        <!-- Sidebar -->
        <div class="col app-calendar-sidebar flex-grow-0 overflow-hidden d-flex flex-column" id="app-calendar-sidebar">
          <div class="sidebar-wrapper">
            
            <div class="card-body pb-0">
              <h5 class="section-label mb-1">
                <span class="align-middle">Filter</span>
              </h5>
              <div class="custom-control custom-checkbox mb-1">
                <input type="checkbox" class="custom-control-input select-all" id="select-all" checked />
                <label class="custom-control-label" for="select-all">View All</label>
              </div>
              <div class="calendar-events-filter">
                <div class="custom-control custom-control-danger custom-checkbox mb-1">
                  <input
                    type="checkbox"
                    class="custom-control-input input-filter"
                    id="personal"
                    data-value="personal"
                    checked
                  />
                  <label class="custom-control-label" for="personal">Draft</label>
                </div>
                <div class="custom-control custom-control-primary custom-checkbox mb-1">
                  <input
                    type="checkbox"
                    class="custom-control-input input-filter"
                    id="business"
                    data-value="business"
                    checked
                  />
                  <label class="custom-control-label" for="business">Upcoming</label>
                </div>
                
                <div class="custom-control custom-control-success custom-checkbox mb-1">
                  <input
                    type="checkbox"
                    class="custom-control-input input-filter"
                    id="holiday"
                    data-value="holiday"
                    checked
                  />
                  <label class="custom-control-label" for="holiday">Archived</label>
                </div>
               
              </div>
            </div>
          </div>
          <div class="mt-auto">
            <img
              src="{{ asset('images/pages/calendar-illustration.png') }}"
              alt="Calendar illustration"
              class="img-fluid"
            />
          </div>
        </div>
        <!-- /Sidebar -->
  
        <!-- Calendar -->
        <div class="col position-relative">
          <div class="card shadow-none border-0 mb-0 rounded-0">
            <div class="card-body pb-0">
              <div id="calendar"></div>
            </div>
          </div>
        </div>
        <!-- /Calendar -->
        <div class="body-content-overlay"></div>
      </div>
    </div>
</section>

<section class='list-view' id="responsive-datatable">
    <div id="filter-box" class="row collapse">
        @role('super_admin')
            <div class="col-md-3 col-12 ">
                <div class="form-group">
                    <label for="event_admin">Event Admin</label>
                    <div class="position-relative">
                        <select name="event_admin" id="event_admin" class="form-control select-tags">
                            <option value="">All</option>
                            <option value="{{  auth()->user()->id }}">Me</option>
                            @foreach($admins as $admin)
                                <option value="{{  $admin->id }}">{{  $admin->fullName() . " - " . $admin->email }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endrole
    
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label for="event_status">Event Status {!! Helper::tooltipInfo("Q. Would you like to be contacted by interested recruiters?") !!}</label>
                <div class="position-relative">
    
                    <select name="event_status" id="event_status" class="form-control">
                        <option value="">All</option>
                        @foreach(config('setting.event_status') as $key => $value)
                            <option value="{{  $key }}">{{  $value }}</option>
                        @endforeach
                    </select>
    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-primary">
                <div class="alert-body">
                    <strong>Note:</strong> Please make sure you change the status for finished event to "Archive" otherwise they will appear on website.
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom d-none">
                    <h4 class="card-title">Events</h4>
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
                                @role('super_admin')
                                    <th>Event Admin</th>
                                @endrole
                                <th>Title</th>
                                <th>Start Date & Time</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Action</th>
                                @role('super_admin')
                                    <th>Last Name</th>
                                    <th>Email</th>
                                @endrole
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<input type="hidden" id="calendar-events" value="{{ route('events.index') . "?view=calendar" }}">
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    @include('inc/select2/scripts')
    @include('inc/form/scripts')
    @include('inc/sweet-alert/scripts')
    
    @include('inc/datatable/scripts')
    
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/calendar/fullcalendar.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection


  
@section('page-script')
   
    <script src="{{ asset(mix('js/scripts/pages/app-calendar-events.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/pages/app-calendar.js')) }}"></script>
    <script>
        $(document).ready(function() {
            let role = $("#app_role").val();
            let columns = [];
            if(role == 'super_admin') {
                columns = [
                        {
                            data: 'users',
                            name: 'user.name'
                        },
                        {
                            data: 'title'
                        },
                        {
                            data: 'start_date_time'
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
                        {
                            data: 'last_name',
                            name: 'user.last_name',
                            visible: false
                        },
                        {
                            data: 'email',
                            name: 'user.email',
                            visible: false
                        },
                    ];
            } else {
                columns = [
                        {
                            data: 'title'
                        },
                        {
                            data: 'start_date_time'
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
                    ];
            }
            var datatable = $("#datatable").DataTable({
                ajax: {
                    url: route('events.index'),
                    data: function(d) {
                        d.view = $("input[name='view']").val();
                        d.event_admin = $("#event_admin").find('option:selected').val();
                        d.event_status = $("#event_status").find('option:selected').val();
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
                columns: columns,
                drawCallback: function() {
                    $("*[data-toggle='tooltip']").tooltip();
                },
                "order": [
                    [2, "desc"]
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

            $(".list-view-btn").click(function() {
                $(this).addClass('d-none');
                $(".calendar-view-btn").removeClass("d-none");
                $(".list-view").removeClass('d-none');
                $(".calendar-view").addClass('d-none');
                datatable.columns.adjust();
            });
            

            // Filters
            $("body").on("change", "#event_status, #event_admin", function() {
                datatable.draw();
            });
        
        });

        function eventClickCallback(info) {
            $("#dynamic-modal").modal();
			$("#dynamic-modal")
				.find(".modal-title")
				.html("Edit Event");

			getContent({
				url: route('events.edit', info.event.id),
				success: function(html) {
					$(".dynamic-content").html(html);
					window.canBlock = false;
					try {
						dynamicScript();
						initTooltip();
						feather.replace({
							width: 14,
							height: 14,
						});
					} catch (e) {}
				},
			});
        }

        function dynamicScript() {
            let formValidator = $("#form").validate();

            $(".modal-dialog").addClass('modal-lg');

            submitForm($("#form"), {
                formValidator: formValidator,
                submitBtn: "#submit",
                successCallback: function(response) {
                    refetchEvents();
                    try {
                        datatable.columns.adjust();
                    } catch(e) {}
                }
            });

            // Date & Time
            if ($('.date-time-picker').length) {
                $(".date-time-picker").flatpickr({
                    enableTime: true,
                    altInput: true,
                    altFormat: 'd M Y h:i K'
                });
            }

            initSelectTag();
        }

        
    </script>
@endsection
