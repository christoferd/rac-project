@props(['phoneNumber', 'class' => 'text-inherit', 'size' => '4'])
@php
    $loc = ($phoneNumber?'tel:+'.config('rental.phone_number.country_code').ltrim(StringLib::digitsOnly($phoneNumber),'0'):'');
@endphp
<a href="{!! $phoneNumber?$loc:'javascript:(0)' !!}" class="{!! $class !!}" {!! ($phoneNumber?'':'disabled') !!}
    {!! $attributes->except(['class', 'size', 'phone-number', 'phoneNumber']) !!}
    x-on:click.stop=""
>
    <x-heroicon-o-device-phone-mobile class="w-{!! $size !!} h-{!! $size !!}"/>
</a>
