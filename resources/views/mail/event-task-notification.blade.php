@component('mail::message')
Hello, <br>

@component('mail::panel')
{{ $content }}
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
