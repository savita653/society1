<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ asset("images/logo/Logo_black_nw.png") }}" style="max-width: 300px; height: auto;" alt="{{  config('app.name') }}">

{{-- {{ $slot }} --}}
@endif
</a>
</td>
</tr>
