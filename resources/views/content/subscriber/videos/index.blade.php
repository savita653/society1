@extends('layouts/contentLayoutMaster')

@section('title', 'Videos')

@section('vendor-style')

@endsection

@section('page-style')

@endsection

@section('breadcrumb_right')

@endsection

@section('content')
    <section class='event-section'>
        <div class="">
            <div class="section-inner">
                <div class="home-event-sec">
                    <div class="event-head-main">
                        <h2>Videos</h2>
                        <form action="">
                            <div class="subject-main">
                                <input type="search" name="s" value="{{ request()->get('s') }}"
                                    placeholder="Enter Keyword..." class='form-control keyword-input'>
                                <input type="submit" value="search" />
                            </div>
                        </form>
                    </div>
                    <div class="event-content-sec">
                        @if( !empty( request()->get('s') ) )
                            <div class="row search-result-heading">
                                <div class="col-12">
                                    <h2>
                                        <span class='text-dark'>Search results for:</span> {{  request()->get('s') }}
                                    </h2>
                                </div>
                            </div>
                        @endif
                        @if ($videos->count())
                            <div class="row mb-2 ">
                                @foreach ($videos as $video)
                                    <div class="col-md-4 col-12 mb-2">
                                        <x-video :video="$video" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="row mb-4 text-center">
                                <div class="col-12">
                                    {{ $videos->links() }}
                                </div>
                            </div>
                        @else
                            <div class="row justify-content-center">
                                <div class="col-md-6 col-12 text-center">
                                    <h2 class="mb-3">Oops! Nothing found.</h2>
                                    <img class='img-fluid' src="{{ asset('images/app/microscope_girl.png') }}" alt="">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')

@endsection



@section('page-script')

@endsection
