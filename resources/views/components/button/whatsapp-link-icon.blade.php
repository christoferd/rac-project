@props(['clientId', 'vehicleId' => 0, 'rentalId' => 0, 'class' => 'text-inherit', 'size' => '4', 'target' => '_blank'])
<a href="{!! route('text-messages.select', $clientId) !!}?vehicle-id={!! $vehicleId !!}&rental-id={!! $rentalId !!}"
   class="{!! $class !!}" x-on:click.stop="" target="{!! ($target?:'_self') !!}">
    <x-heroicon-o-chat-bubble-oval-left class="w-{!! $size !!} h-{!! $size !!}"/>
</a>
