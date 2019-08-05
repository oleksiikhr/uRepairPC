@component('mail::message')
Ваш новий email в системі:

@component('mail::panel')
    {{ $email }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
    Перейти на сайт
@endcomponent
@endcomponent
