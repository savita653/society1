@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

@section('page-style')
@endsection

@section('content')
    </div>
    <div class="card">
        
        <div class="card-body wel-page-main">

            <div class="row">
                <div class="col-12 ">
                    <div class="event-head-main mb-2 text-center">
                        <h2 class="w-100">Welcome {{  auth()->user()->fullName() }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <h5>
                        You have not subscribed to {{ config('app.name') }}. <a href="{{ route('subscriber.setup') }}" class="sub-btn clk-btn">Click
                            Here</a> to get started.
                    </h5>
                </div>
            </div>


            @if (!auth()->user()->hasRole('presenter'))
                <div class="row mt-1">
                    <div class="col-12 text-center">
                        <h5>
                            Interested in presenting? <a href="{{ route('apply.presenter') }}" class="sub-btn clk-btn">Start Here</a>
                        </h5>
                    </div>
                </div>
            @else
                <div class="row mt-1">
                    <div class="col-12 text-center">
                        <h5>
                            <a href="{{ route('presenter.events.index') }}" class="prsnt-btn clk-btn">Click Here</a> to access your Presenter Dashboard
                        </h5>
                    </div>
                </div>
            @endif

            <div class="row mt-1 mb-3 welcm-graph-img">
                <div class="col-12 text-center">
                    <img src="{{  asset('images/app/microscope_girl.png') }}" alt="">
                </div>
            </div>

        </div>
    </div>
@endsection



@section('page-script')

@endsection
