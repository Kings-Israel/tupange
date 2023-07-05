@component('mail::message')
Hello, <br>

@component('mail::panel')
{{ $content }}
@endcomponent

@component('mail::button', ['url' => $url])
Login
@endcomponent

Regards,<br>
{{ config('app.name') }}
@endcomponent
