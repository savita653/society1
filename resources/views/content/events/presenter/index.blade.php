@extends('layouts/contentLayoutMaster')

@section('title', 'Events')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/calendars/fullcalendar.min.css')) }}">
    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/sweet-alert/styles')
    @include('inc/select2/styles')

     <!-- vendor css files -->
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-calendar.css')) }}">
@endsection

@section('breadcrumb_right')
    <button data-toggle='tooltip' title='Calendar View' class="btn-icon btn btn-primary rounded-circle btn-sm calendar-view-btn"
    type="button" aria-haspopup="true" aria-expanded="false">
    <i data-feather="calendar"></i>
    </button>
    <button data-toggle='tooltip' title='List View' class="btn-icon btn btn-primary rounded-circle btn-sm list-view-btn d-none"
    type="button" aria-haspopup="true" aria-expanded="false">
    <i data-feather="list"></i>
    </button>
@endsection

@section('content')
    <section class="list-view" id="responsive-datatable">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-2">My Events</h1>
                <div class="card">
                    <div class="card-header border-bottom d-none">
                        <h4 class="card-title">Events</h4>
                    </div>
                    <div class="card-datatable">
                        
                        <input type="hidden" name="view" value="all">
                        
                        <table id="datatable" class="dt-responsive table">
                            <thead>
                                <tr>
                                    <th>Event Admin</th>
                                    <th>Title</th>
                                    <th>Start Date & Time</th>
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
    <input type="hidden" id="calendar-events" value="{{ route('presenter.events.index') . "?view=calendar" }}">
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    @include('inc/datatable/scripts')
    @include('inc/form/scripts')
    @include('inc/sweet-alert/scripts')
    @include('inc/select2/scripts')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/calendar/fullcalendar.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
@endsection


  
@section('page-script')
    <script src="{{ asset(mix('js/scripts/pages/app-calendar-events.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/pages/app-calendar.js')) }}"></script>
    <script>
        $(document).ready(function() {
            let columns = [];
            columns = [
                    {
                        data: 'user_name',
                        name: 'users.name'
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
            ];
            var datatable = $("#datatable").DataTable({
                ajax: {
                    url: route('presenter.events.index'),
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
           

            $(".list-view-btn").click(function() {
                $(this).addClass('d-none');
                $(".calendar-view-btn").removeClass("d-none");
                $(".list-view").removeClass('d-none');
                $(".calendar-view").addClass('d-none');
                datatable.columns.adjust();
            });
        });

        function eventClickCallback(info) {
            $("#dynamic-modal").modal();
			$("#dynamic-modal")
				.find(".modal-title")
				.html("Event Information");

			getContent({
				url: route('presenter.events.show', info.event.id),
				success: function(html) {
					$(".dynamic-content").html(html);
					window.canBlock = false;
					try {
						initTooltip();
						feather.replace({
							width: 14,
							height: 14,
						});
					} catch (e) {}
				},
			});
        }

    </script>
@endsection
