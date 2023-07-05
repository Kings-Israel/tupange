<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ asset('assets/images/assets/logo-1.png') }}" style="width: 120px">
{{-- {{ $slot }} --}}
@endif
</a>
</td>
</tr>
