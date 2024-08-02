@props(['placement'=>'top', 'tip'=>''])
<div class="hs-tooltip inline-block">
    <div class="hs-tooltip-toggle relative top-0.5 [--placement:{!! $placement !!}]">
        {!! $slot !!}
        <span
            class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white"
            role="tooltip">
            {!! $tip !!}
            {!! $content??'' !!}
        </span>
    </div>
</div>
