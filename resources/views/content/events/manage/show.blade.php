
<div class="row">
    <div class="col-12">
        
        <div class="">
            <img  class="img-fluid rounded event-image" src="{{ $record->getImage() }}" alt="" id="logo-placeholder" />
        </div>

        <div class="mt-1">
            <div class="row">
                <div class="col-12">
                    <h4>Event Admin</h4>
                    <p>
                        {{ $record->user->fullName() }} - <a href="mailto:{{ $record->user->email }}">{{ $record->user->email }}</a>
                    </p>
                </div>
                <div class="col-6">
                    <h4>Title</h4>
                    <p>
                        {{ $record->title }}
                    </p>
                </div>
                <div class="col-6">
                    <h4>Date & Time</h4>
                    <p>
                        @displayDate($record->start_date_time, 'd M Y h:i A')
                    </p>
                </div>
            </div>
           
            <div class="row">
                <div class="col-12">
                    <h4>Keywords</h4>
                    <p>
                        @forelse($record->keywords as $keyword)
                            <span class="btn btn-light">{{ $keyword->keyword_name }}</span>
                        @empty
                            N/A
                        @endforelse
                    </p>
                </div>
                <div class="col-12">
                    <h4>Presenters </h4>
                    @forelse($record->presenters as $presenter)
                        <p>{{ $presenter->fullName() }} - <a href="mailto:{{ $presenter->email }}">{{ $presenter->email }}</a></p>
                    @empty
                        N/A
                    @endforelse
                </div>
            </div>
            <x-form-element class='d-none' type='simple'>
                <x-slot name="label">
                    <label class="form-label" for="status">Status</label>
                </x-slot>
                <div>
                    {!! $record->statusHtml() !!}
                </div>
            </x-form-element>

          

        </div>
    </div>
    
</div>
