@extends('layouts/contentLayoutMaster')

@section('title', 'User Activities')

@section('vendor-style')
    @include('inc/datatable/styles')
    @include('inc/form/styles')
    @include('inc/select2/styles')
    @include('inc/sweet-alert/styles')
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('css/pages/app-chat.css') }}" />
    <style>
        .modal-dialog-aside {
            width: 44% !important;
        }

    </style>
@endsection

@section('content')
    <section id="responsive-datatable">
        <div class="row align-items-end mb-2">
            <div class="col-md-4 col-12">
                    <label for="intake">Filter by User</label>

                    <div class="position-relative">
                        <select data-live-search='true' data-style="bg-white border-light" class="select ajax-select form-control"
                            name="user" id="user">
                            <option value="">All</option>
                            @foreach (config('users') as $user)
                                <option {{ request()->get('user_id') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">
                                    {{ \Str::limit($user->fullName() . ' - ' . $user->email, 40, '...') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary clear-filter">Clear</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-bottom d-none">
                        <h4 class="card-title">User Activities</h4>
                    </div>
                    <div class="card-datatable">
                        
                        <table id="datatable" class="dt-responsive table">
                            <thead>
                                <tr>
                                    <th>Action Date</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>User</th>
                                    <th>Log</th>
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
    @include('inc/select2/scripts')
    @include('inc/sweet-alert/scripts')
@endsection


@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    <script>
        $(document).ready(function() {
            $(".clear-filter").click(function() {
                $('.ajax-select').empty();
                $('.ajax-select').select2("val", "");
                dataTable.draw();
            })
            var messgeTable;
            let dataTable;
            dataTable = $("#datatable").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: route('super-admin.user_activities'),
                    data: function(d) {
                        d.user_id = $("#user").val();
                    }
                },
                //dom: "tps",
                "order": [
                    [0, "desc"]
                ],
                columns: [{
                        name: 'created_at',
                        data: 'created_at'
                    },

                    {
                        name: 'description',
                        data: 'description'
                    },

                    {
                        name: 'ip_address',
                        data: 'ip_address'
                    },

                    {
                        name: 'user',
                        data: 'user',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'properties',
                        data: 'properties',
                        visible: false,
                        // orderable: false,
                        // searchable: false
                    },


                ],
                responsive: false,

                drawCallback: function(setting, data) {

                    let setTime;
                    clearTimeout(setTime);
                    setTime = setTimeout(() => {
                        // messgeTable.draw('page');
                    }, 10000);

                },
                bInfo: true,
                pageLength: 100,
                initComplete: function(settings, json) {

                    // setInterval(()=>{
                    //   dataTable.ajax.reload(null,false);
                    // },5000);
                }
            });

            $("body").on("click", ".view-log", function() {
                $(".dynamic-title").html("Raw Logs <sup>Development</sup>");
                getContent({
                    url: $(this).data('url'),
                    success: function(data) {
                        $('.dynamic-body').html(data);
                    }
                });
            });

            $("#user").change(function() {
                // $("#tutor").val("");
                // $("#tutor").selectpicker('refresh');
                dataTable.draw();
            });
            $("#tutor").change(function() {
                // $("#user").val("");
                // $("#user").selectpicker('refresh');
                dataTable.draw();
            });

            $('.ajax-select').select2({
                placeholder: 'Select User',
                ajax: {
                    url: route('users.get'),
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.name + " " + item.email,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

        });

    </script>
@endsection
