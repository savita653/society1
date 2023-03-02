@extends('layouts/contentLayoutMaster')

@section('title', 'Home')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('plugins/owl_carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owl_carousel/css/owl.theme.default.min.css') }}">
@endsection

@section('content')
    @if (auth()->user()->subscribed('default') ||
        1)
        <div class="event-main">
            <div class="section-inner">
                <div class="home-event-sec">
                    <div class="event-head-main">
                        <h2>Upcoming Presentations</h2>
                        <ul class="arrow-icons-list">
                            <li><a data-type="event" href="javascript:void();" data-direction="prev" class="left-icon owl-custom-nav"></a>
                            </li>
                            <li><a data-type="event" href="javascript:void();" data-direction="next" class="right-icon owl-custom-nav"></a>
                            </li>
                        </ul>
                        <form action="{{ route('subscriber.events.index') }}">
                            <div class="subject-main">
                                <input required type="search" name="s" value="{{ request()->get('s') }}"
                                    placeholder="Enter Keyword..." class='form-control keyword-input'>
                                <input type="submit" value="search" />
                            </div>
                        </form>
                    </div>
                    <div class="event-content-sec">
                        <div class="events-list row owl-carousel owl-theme owl-upcoming-events w-100">
                            @forelse ($events as $event)
                                <x-event :event="$event" />
                            @empty
                                <h1 class="empty-events text-center text-dark">No Presentations yet.</h1>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($events->count() != 0)
            <div class="row my-2">
                <div class="col-12 text-center">
                    <a href="{{ route('subscriber.events.index') }}" class="btn btn-primary btn-lg">Show All</a>
                </div>
            </div>
        @endif
        <div class="feat-subcibe-main">
            <div class="section-inner">
                <div class="home-event-sec feat-video-sec">
                    <div class="event-head-main feat-video-main">
                        <h2>Recent Videos</h2>
                        <ul class="arrow-icons-list">
                            <li><a href="javascript:void();" data-type="video" data-direction="prev" class="left-icon owl-custom-nav"></a>
                            </li>
                            <li><a href="javascript:void();" data-type="video" data-direction="next" class="right-icon owl-custom-nav"></a>
                            </li>
                        </ul>
                        <form action="{{ route('subscriber.videos.index') }}">
                            <div class="subject-main">
                                <input required type="search" name="s" value="{{ request()->get('s') }}"
                                    placeholder="Enter Keyword..." class='form-control keyword-input'>
                                <input type="submit" value="search" />
                            </div>
                        </form>
                    </div>
                    <div class="event-content-sec feat-video-content-sec">
                        <div class="videos-list row owl-carousel owl-theme owl-recent-videos w-100">
                            @forelse ($videos as $video)
                                <x-video :video="$video" />
                            @empty
                                <h1 class="empty-video text-center text-dark">No Videos yet.</h1>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($videos->count() != 0)
            <div class="row my-2 show-all-button">
                <div class="col-12 text-center">
                    <a href="{{ route('subscriber.videos.index') }}" class="btn btn-primary btn-lg">Show All</a>
                </div>
            </div>
        @endif
    @else
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title">Welcome {{ auth()->user()->fullName() }}</h4>
            </div>
            <div class="card-body ">

                <div class="row">
                    <div class="col-12">
                        You have not subscribed to {{ config('app.name') }}. <a
                            href="{{ route('subscriber.setup') }}">Click Here</a> to get started.
                    </div>
                </div>

                @if (!auth()->user()->hasRole('presenter'))
                    <div class="row mt-1">
                        <div class="col-12">
                            Interested in presenting? <a href="{{ route('apply.presenter') }}">Start Here</a>
                        </div>
                    </div>
                @else
                    <div class="row mt-1">
                        <div class="col-12">
                            <a href="{{ route('presenter.events.index') }}">Click Here</a> to access your Presenter
                            Dashboard
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
    
@endsection


@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset('plugins/owl_carousel/js/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let upcomingEventsOwl = $('.owl-upcoming-events').owlCarousel({
                loop: false,
                margin: 30,
                nav: false,
                center: $(".empty-events").length ? true : false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 3
                    }
                }
            });

            let upcomingRecentVideo = $('.owl-recent-videos').owlCarousel({
                loop: false,
                margin: 20,
                nav: false,
                center: $(".empty-videos").length ? true : false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 4
                    },
                    1000: {
                        items: 4
                    }
                }
            });

            $(".owl-custom-nav").on("click", function() {
                let direction = $(this).data('direction');
                switch($(this).data('type')) {
                  case 'event':
                    upcomingEventsOwl.trigger(`${direction}.owl.carousel`);
                    break;
                    
                  case 'video':
                    upcomingRecentVideo.trigger(`${direction}.owl.carousel`);
                    break;
                }
            });

        });

    </script>
@endsection
