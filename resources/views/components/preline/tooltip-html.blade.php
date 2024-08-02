<div class="hs-tooltip inline-block">
    <div class="hs-tooltip-toggle ptr">
        {!! $slot !!}
        <span
            class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-white"
            role="tooltip">
          {!! $tip !!}
        </span>
    </div>
</div>
