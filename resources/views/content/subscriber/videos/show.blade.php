@extends('layouts/contentLayoutMaster')

@section('title', 'Video - ' . $video->title)

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
    
    <div class="row mb-2">
        <div class="col-12 col-md-8">
            <div class="stream-head">
                <h2 class="heading">{{ $video->title }}</h2>
            </div>
            <video controlsList="nodownload" class='w-100' src="{{ config('setting.s3_url') . $video->path }}" controls></video>
        </div>
        <div class="col-12 col-md-4">
            <div class="stream-head">
                <h2 class="heading">Replay Chat</h2>
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
                       

                        <!-- User Chat messages -->
                        <div class="user-chats" style="height: 70vh;">
                            <div class="chats chat-message-box">
                            </div>
                        </div>
                        <!-- User Chat messages -->

                    </div>
                    <!--/ Active Chat -->
                </section>
                <!--/ Main chat area -->
            </div>
        </div>
    </div>
    

    @if($videos->count())
        <div class="row mt-4">
            <div class="col-12">
				<div class="rel-event">
                    <h2>Related Videos</h2>
                    <div class="row">
                        @foreach ($videos as $videoRecord)
                            <div class="col-md-4 col-12 mb-2">
                                <x-video :video="$videoRecord" />
                            </div>
                        @endforeach
                    </div>
				</div>
            </div>
        </div>
    @endif
    
    @if($video->event_id)
        <input type="hidden" id="event-id" name="event_id" value="{{ $video->event_id }}" />
    @endif
@endsection

@section('vendor-script')
    {{-- vendor files --}}
    @include('inc/sweet-alert/scripts')
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.4.2/firebase-database.js"></script>
    <script>

    </script>
@endsection



@section('page-script')
    <script src="{{ asset(mix('js/scripts/pages/app-chat.js')) }}"></script>
    {{-- <script src="{{ asset('js/scripts/event.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            if( $("#event-id").length ) {
                let eventId = $("#event-id").val();
                firebase.initializeApp(window.Laravel.firebaseConfig);
                firebase
                    .database()
                    .ref("eventPublicMessage/" + eventId)
                    .on("child_added", function (snapshot) {
                        createMessageHtml(snapshot);
                        return;
                    });
            }
        });

        function createMessageHtml(snapshot) {
            let object = snapshot.val();
            let profileImageUrl = url("images/avatars/profile.png");
            if (object.user.profile_photo_path) {
                profileImageUrl = url(
                    `uploads/profile_pic/web/${object.user.profile_photo_path}`
                );
            }
            let chatClass = "";
            if (window.Laravel.user.id != object.user.id) {
                chatClass = "chat-left";
            }
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
            ];
            
            let date = new Date(object.created_at);
            date = `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
            let chatHtml = `
            <div class="chat ${chatClass}">
                <div class="chat-avatar">
                <span class="avatar box-shadow-1 cursor-pointer">
                    <img src="${profileImageUrl}" alt="avatar" height="36" width="36">
                </span>
                </div>
                <div class="chat-body">
                <div class="chat-content">
                    <p>${object.message}</p>
                    <small class='mt-50 d-block'>${object.user.name} - ${date}</small>
                </div>
            </div>
        `;

            $(".chat-message-box").append(chatHtml);
            $(".message").val("");
            // $(".user-chats").scrollTop($(".user-chats > .chats").height());
        }
    </script>
@endsection
