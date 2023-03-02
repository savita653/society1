
<a href="{{ route('subscriber.events.show', $event->id) }}">
    <div class="event-new-list">
        <div class="event-img-sec">
            <img src="{{ $event->getImage() }}" class="events-img" alt="{{ $event->title }}"/>
            <span class="time">
                <img src="{{ asset('images/app/timer-icon.png') }}"/>
                @displayDate($event->start_date_time, "h:i A")
            </span>
        </div>
        <div class="event-head-sec">
            <h3>{!! Helper::tooltip($event->title, 50) !!}</h3>
            <h4>@displayDate($event->start_date_time, "d")<span>@displayDate($event->start_date_time, "D F")</span></h4>
        </div>
    </div>
</a>