
@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
  <div class="row match-height">

    @if( auth()->user()->subscribed('default') ) 
      <div class="col-xl-10 col-md-6 col-12">
        <div class="card card-statistics">
          <div class="card-header">
            <h4 class="card-title">Welcome {{ auth()->user()->fullName() }}</h4>
          </div>
          <div class="card-body statistics-body">
            <div class="row">
              <div class="col-xl-2 col-sm-6 col-12 mb-2 mb-xl-0">
                <div class="media">
                  <div class="avatar bg-light-primary mr-2">
                    <div class="avatar-content">
                      <i data-feather="user" class="avatar-icon"></i>
                    </div>
                  </div>
                  <div class="media-body my-auto">
                    <a href="{{ route('account') }}">
                        <h4 class="font-weight-bolder mb-0">Account</h4>
                        <p class="card-text font-small-3 mb-0">View</p>
                    </a>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    @else
        <div class="row w-100">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p>You have not subscribed to {{ config('app.name') }}</p>
                        <a href="{{ route('subscriber.setup') }}">Click Here</a> to become a subscriber.
                    </div>
                </div>
            </div>
        </div>
    @endif
  </div>
</section>
<!-- Dashboard Ecommerce ends -->
@endsection


@section('page-script')
  {{-- Page js files --}}
@endsection
