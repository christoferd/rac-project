@props(['class'=>'pb-30 w-full'])
<div x-data="{dataSaved: $wire.$entangle('dataSaved', true)}"
     x-on:keydown="dataSaved=false"
     x-on:click="dataSaved=false"
     class="{!! $class !!}">
    @include('alpine.data-status-modified-saved')
    {!! $slot !!}
</div>
