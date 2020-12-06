@component('mail::message')
Ваш тимчасовий пароль в системі:

@component('mail::panel')
    <strong>{{ $password }}</strong>
@endcomponent

Змінити його можна в своєму профілі.

@component('mail::button', ['url' => url('/')])
    Перейти на сайт
@endcomponent
@endcomponent
