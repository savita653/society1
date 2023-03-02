@extends('layouts/contentLayoutMaster')

@section('title', 'Event - ' . $event->title)

@section('vendor-style')

    @include('inc/sweet-alert/styles')
    <!-- vendor css files -->
    <style>
        .agora_video_player {
            position: unset !important;
        }

    </style>
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-chat-list.css')) }}">
    <style>
        body {
            background-color: #fff !important;
        }

        

    </style>
@endsection

@section('breadcrumb_right')

@endsection


@section('content')
    

    <div class="row live-body">
        <div class="col-md-6 col-12 ">
            <div class="flex flex-row">
                <div class="item">
                    <div class="row mb-25">
                        <div class="col-12 col-md-6 live-row">
                            <div class="stream-head">
                                <h2 class="heading">Live Stream</h2>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 text-right">
                            <button data-icon="mic-off" class="btn d-none btn-outline-primary mute-btn btn-icon toggle-mic">
                                Unmute <i data-feather="mic-off"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="item">
					<div class="row align-items-center live-feed-container shadow-sm mb-2" >
						<div class="col-12">

                            <div class="d-flex flex-column justify-content-start text-center message-box" style="max-height: 700px">
                                
                                <div class="item message-box-text mb-4">
                                    <h3 class="mb-2">Event will start on @displayDate($event->start_date_time, 'd M Y h:i A') </h3>
                                
                                    <button class="btn single-prd-btn calendr-btn btn-dark dropdown-toggle " type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather='calendar'></i> Add to Calendar
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($event->calendarLinks() as $key => $link)
                                            <a class="dropdown-item" {{ $key == 'apple' ? 'target="_blank"' : '' }} href="{{ $link['link'] }}">
                                                {!! isset($link['icon']) ? "<i data-feather='calendar'></i>" : '' !!}
                                                {{ $link['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                    <button class="btn btn-primary single-prd-btn request-reminder mt-md-0 mt-2" data-type='reminder' data-modeltype='event'
                                        data-modelid="{{ $event->id }}">
                                        <i data-feather='bell'></i> 
                                        <span>
                                            {{ $event->isReminderRequested() ? 'Cancel Reminder' : 'Request Reminder' }}
                                        </span>
                                    </button>
                                </div>
                                <div class="item justify-self-end">
                                    {{-- <img class='img-fluid' src="{{  asset('images/app/video_player.png') }}" alt="Video Player"> --}}
                                    <img style="max-height: 400px; width: 500px; border-radius: 5px;" class='img-fluid' src="{{ $event->getImage() }}" alt="{{ $event->title }}">
                                </div>
                                    
                            </div>
							<div  id="local-player" class="player">
		
							</div>

						</div>
					</div>
                </div>

                <div class="item">
                    <div class="row">
                        <div class="col-12">
                            
                            @foreach($event->users ?? [] as $user)
                                @if($user->hasRole('admin')) @continue @endif
                                <div class="row align-items-center about-presenter-box d-none about-presenter-{{ $user->id }} py-2 mb-2 shadow-sm">
                                    <div class="col-12 mb-2">
                                        <h3>About Presenter</h3>
                                    </div>
                                    <div class="col-4 d-flex align-items-center ">
                                        <div class="avatar avatar-xl d-inline-block">user
                                            <img src="{{ $user->profileImage() }}" alt="{{  $user->fullName() }}">
                                        </div>
                                        <div class="d-inline-block pl-2">
                                            <h5>{{  $user->fullName() }}</h5>
                                            <a href="mailto:{{ $user->email }}">{{  $user->email }}</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 mt-md-0 mt-2">
                                        <h5>Institution</h5>
                                        <p>
                                            {{ $user->getMeta('institution_name') }}
                                            <small class='d-block'>{{ $user->institutionAddress() }}</small>
                                        </p>
                                    </div>
                                    <div class="col-md-4 col-12 ">
                                        <h5>Department</h5>
                                        <p>
                                            {{ $user->getMeta('department') }}
                                        </p>
                                    </div>
                                    
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
		<div class="col-md-1 col-12 spacing-bar">
			<div class="row">
				
			</div>
		</div>
        <div class="col-md-5 col-12 ">
			<div class="event-chat-sec">
            <ul class="nav nav-tabs mb-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active pt-0" id="information-tab" data-toggle="tab" href="#information"
                        aria-controls="home" role="tab" aria-selected="true"> <i data-feather="info"></i> Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pt-0" id="chat-box-tab" data-toggle="tab" href="#chat-box" aria-controls="profile"
                        role="tab" aria-selected="false">
                        <i data-feather="message-square"></i> Chat</a>
                </li>

            </ul>
			</div>
            <div class="tab-content pt-75">
                <div role="tabpanel" class="tab-pane active " id="information" aria-labelledby="chat-box"
                    aria-expanded="true">
					
					<div class="row mb-3">
						{{-- <div class="col-12 mb-2">
							<img  class="img-fluid rounded event-image" src="{{ $event->getImage() }}" alt="{{  $event->title }}" id="logo-placeholder" />
						</div> --}}
						<div class="col-md-6 col-12 mb-2">
							<div class="event-head">
							<h4>Title</h4>
							<p>
								{{ $event->title }}
							</p>
							</div>
						</div>
						<div class="col-md-6 col-12 mb-2">
							<div class="event-head">
							<h4>Date & Time</h4>
							<p>
								@displayDate($event->start_date_time, "d M Y | h:i A")
							</p>
							</div>
						</div>
						@if($event->keywords)
							<div class="col-12 mb-2">
								{{-- <h4>Keywords</h4> --}}
								<p>
									@foreach($event->keywords as $keyword)
										<span class="btn btn-light no-cursor mr-50 mb-50" >{{ $keyword->keyword_name }}</span>
									@endforeach
								</p>
							</div>
						@endif
					</div>

				</div>

                <div role="tabpanel" class="tab-pane border" id="chat-box" aria-labelledby="chat-box" aria-expanded="true">
                    <!-- Main chat area -->
                    <section class="chat-app-window">
                        <!-- To load Conversation -->
                        <div class="start-chat-area d-none" style="height: 70vh;">
                            <div class="mb-1 start-chat-icon">
                                <i data-feather="message-square"></i>
                            </div>
                            <h4 class="sidebar-toggle start-chat-text">Chat not enabled</h4>
                        </div>
                        <!--/ To load Conversation -->

                        <!-- Active Chat -->
                        <div class="active-chat">
                            <!-- Chat Header -->
                            <div class="chat-navbar">
                                <header class="chat-header d-none">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar-toggle d-block d-lg-none mr-1">
                                            <i data-feather="menu" class="font-medium-5"></i>
                                        </div>
                                        <div class="avatar avatar-border user-profile-toggle m-0 mr-1">
                                            <img src="{{ asset('images/portrait/small/avatar-s-7.jpg') }}" alt="avatar"
                                                height="36" width="36" />
                                            <span class="avatar-status-busy"></span>
                                        </div>
                                        <h6 class="mb-0">Kristopher Candy</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i data-feather="phone-call"
                                            class="cursor-pointer d-sm-block d-none font-medium-2 mr-1"></i>
                                        <i data-feather="video"
                                            class="cursor-pointer d-sm-block d-none font-medium-2 mr-1"></i>
                                        <i data-feather="search" class="cursor-pointer d-sm-block d-none font-medium-2"></i>
                                        <div class="dropdown">
                                            <button class="btn-icon btn btn-transparent hide-arrow btn-sm dropdown-toggle"
                                                type="button" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i data-feather="more-vertical" id="chat-header-actions"
                                                    class="font-medium-2"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="chat-header-actions">
                                                <a class="dropdown-item" href="javascript:void(0);">View Contact</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Mute Notifications</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Block Contact</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Clear Chat</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Report</a>
                                            </div>
                                        </div>
                                    </div>
                                </header>
                            </div>
                            <!--/ Chat Header -->

                            <!-- User Chat messages -->
                            <div class="user-chats" style="height: 70vh;">
                                <div class="chats chat-message-box">
                                </div>
                            </div>
                            <!-- User Chat messages -->

                            <!-- Submit Chat form -->
                            <form class="chat-app-form" action="javascript:void(0);" onsubmit="enterChat();">
								<div class='chat-disabled text-center w-100 d-none'>
									<h4 clas="mb-0"> <i data-feather='message-square'></i> Chat disabled by Event Organizer.</h4>
								</div>
								<div class="input-group chat-element d-none input-group-merge mr-1 form-send-message">
									<input type="text" class="form-control message" placeholder="Type your message..." />
								</div>
								<button type="button" class="btn chat-element d-none btn-primary send" onclick="enterChat();">
									<i data-feather="send" class="d-lg-none"></i>
									<span class="d-none d-lg-block">Send</span>
								</button>
                            </form>
                            <!--/ Submit Chat form -->
                        </div>
                        <!--/ Active Chat -->
                    </section>
                    <!--/ Main chat area -->


                </div>

            </div>
        </div>
    </div>

    @if($events->count())
        <div class="row mt-4">
            <div class="col-12 rel-event-main events-sec-primary">
				<div class="rel-event">
                <h2>Related Events</h2>
                <div class="row">
                    @foreach ($events as $eventRecord)
                        <div class="col-md-4 col-12 mb-2">
                            <x-event :event="$eventRecord" />
                        </div>
                    @endforeach
                </div>
				</div>
            </div>
        </div>
    @endif
    <input type="hidden" id="event-id" value="{{ $event->id }}" />
    <input type="hidden" id="channel-name" value="{{ $event->channel_name }}" />
    <input type="hidden" id="agora-token" value="{{ $event->token }}" />
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    @include('inc/sweet-alert/scripts')
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.4.0.js"></script>
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-database.js"></script>
    <script>

    </script>
@endsection



@section('page-script')
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    <script src="{{ asset('js/scripts/event.js') }}"></script>
@endsection
