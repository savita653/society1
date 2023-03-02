
@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <div class="row match-height">

    <!-- Statistics Card -->
    <div class="col-xl-10 col-md-6 col-12">
      <div class="card card-statistics">
        <div class="card-header">
          <h4 class="card-title">Statistics</h4>
        </div>
        <div class="card-body statistics-body">
          <div class="row">
            <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-xl-0">
              <a href="{{ route('admins.index') }}">
                <div class="media">
                  <div class="avatar bg-light-primary mr-2">
                    <div class="avatar-content">
                      <i data-feather="users" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <h4 class="font-weight-bolder mb-0">{{ $stats['admins_count'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Admins</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-xl-0">
              <a href="{{ route('presenters.index') }}">
                <div class="media">
                  <div class="avatar bg-light-info mr-2">
                    <div class="avatar-content">
                      <i data-feather="users" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <h4 class="font-weight-bolder mb-0">{{ $stats['presenters_count'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Presenters</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-sm-0">
              <a href="{{ route('users.index') }}">
                <div class="media">
                  <div class="avatar bg-light-danger mr-2">
                    <div class="avatar-content">
                      <i data-feather="users" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <h4 class="font-weight-bolder mb-0">{{ $stats['subscribers_count'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Subscribers</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-2 col-sm-6 col-12">
              <a href="{{ route('events.index') }}">
                <div class="media">
                  <div class="avatar bg-light-success mr-2">
                    <div class="avatar-content">
                      <i data-feather="calendar" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <h4 class="font-weight-bolder mb-0">{{ $stats['events_count'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Events</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-xl-2 col-sm-6 col-12">
              <a href="{{ route('videos.index') }}">
                <div class="media">
                  <div class="avatar bg-light-success mr-2">
                    <div class="avatar-content">
                      <i data-feather='video' class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <h4 class="font-weight-bolder mb-0">{{ $stats['videos_count'] }}</h4>
                    <p class="card-text font-small-3 mb-0">Videos</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--/ Statistics Card -->
  </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection


@section('page-script')
  {{-- Page js files --}}
@endsection
