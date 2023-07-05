@component('mail::panel')
Comments submitted by {{ $name }}, Email {{ $email }}
@endcomponent

@component('mail::panel')
{{ $content }}
@endcomponent
