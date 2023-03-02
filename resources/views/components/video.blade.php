@props(['video'])
<a href="{{ route('subscriber.videos.show', $video->id) }}">
    <div class="event-new-list">
        <div class="event-img-sec">
            <img src="{{ $video->getImage() }}" class="featured-video-img w-100" alt="{{ $video->title }}"/>
        </div>
        <div class="event-head-sec">
            <h3>{{ $video->title }}</h3>
        </div>
    </div>
</a>