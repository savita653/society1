
<div class="row">
    <div class=" col-12">
        
        <h4>Subscriber Submission</h4>
        <p>
            <strong>Name:</strong> {{ $user->fullName() }}
        </p>
        <p>
            <strong>Email:</strong> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
        </p>
        <p>
            <strong>Institution Name:</strong> {{ $userMeta['institution_name'] ?? "N/A" }}
        </p>
        <p>
            <strong>Department:</strong> {{ $userMeta['department'] ?? "N/A" }}
        </p>
        <p>
            <strong>Institution Address:</strong>
            <div class="row">
                <div class="col-6"><strong>City:</strong> {{ $userAddress['city'] ?? "N/A" }}</div>
                <div class="col-6"><strong>State:</strong> {{ $userAddress['State'] ?? "N/A" }}</div>
                <div class="col-6"><strong>Postal Code:</strong> {{ $userAddress['postal_code'] ?? "N/A" }}</div>
                <div class="col-6"><strong>Country:</strong> {{ $userAddress['country'] ?? "N/A" }}</div>
            </div>
        </p>
        <p>
            <strong>Areas of Interest</strong>
            <div>
                @forelse(Helper::jsonToArray($userMeta['areas_of_interest'] ?? "{}") as $value)
                    <span class="badge badge-primary">{{ $value }}</span>
                @empty
                    N/A
                @endforelse
            </div>
        </p>
        <p>
            <strong>How did you hear about Researchers LIVE?</strong>
            <div>
                {{ $userMeta['hear_about_us'] ?? "N/A" }}
            </div>
        </p>
        <p>
            <strong>Membership Level:</strong>
            <div>
                @for($i = 1; $i <= 3; $i++)
                    @isset($userMeta['membership_level_' . $i])
                        @php $levelDetail = json_decode($userMeta['membership_level_' . $i], true); @endphp
                        @if($levelDetail['required_textbox'] == 1)
                            <p class="ml-{{ ( ($i - 1) * 2) - 1 }}">{{ $levelDetail['other_value'] }}</p>
                        @else
                            <p class="ml-{{ ( ($i - 1) * 2) - 1 }}">{{ $levelDetail['name'] }}</p>
                        @endif
                    @endisset
                @endfor
            </div>
        </p>
        <p>
            <strong>Would you like to receive email notifications of upcoming events?</strong>
            <div>
                {{ $userMeta['email_notification'] ?? "" == 1 ? "Yes" : "No" }}
            </div>
        </p>
        <p>
            <strong>Would you like to be contacted by interested recruiters?</strong>
            <div>
                {{ $user->can_contact ? "Yes" : "No" }}
            </div>
        </p>
        <p>
            <strong>Subscribe to our newsletter to get latest news and updates?</strong>
            <div>
                {{ $userMeta['newsletter'] ?? "" == 1 ? "Yes" : "No" }}
            </div>
        </p>
       
    </div>
</div>

