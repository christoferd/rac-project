@props(['type' => 'button'])
<div class="text-right">
    <button type="{!! $type !!}" {!! $attributes->merge(['class' => 'btn save']) !!}>
        <x-vaadin-database class="w-4 h-4"/>
        <span>{!! __('Save') !!}</span>
    </button>
</div>
