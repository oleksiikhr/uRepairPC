@component('mail::message')
Нове замовлення:

@component('mail::panel')
    <strong>{{ $request->title }}</strong>
@endcomponent

@component('mail::button', ['url' => url('requests/'.$request->id)])
    Переглянути
@endcomponent
@endcomponent
