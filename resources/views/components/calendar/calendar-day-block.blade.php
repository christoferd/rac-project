@props(['eventInfo' => '', 'textColor' => 'black', 'bgColor' => 'gray-100', 'badge' => '', 'class' => '', 'style' => ''])
{{--    xl:w-40 lg:w-30 md:w-30 sm:w-20 w-10 --}}
<div class="{!! $class !!}" style="{!! $style !!}">
    <div class="flex flex-col mx-auto w-full overflow-hidden">
        {{--        xl:w-40 lg:w-30 md:w-30 sm:w-full w-10 --}}
        <div class="bottom flex-grow w-full cursor-pointer leading-4">
            @if($eventInfo !== '')
                <div class="event bg-{{ $bgColor }} text-{{ $textColor }} rounded p-1 text-sm leading-4 relative">
                    @if($badge !== '')
                        <span
                            class="absolute top-1 right-1 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-gray-100 text-gray-600">
                            {{ $badge }}
                        </span>
                    @endif
                    <span class="event-name">
                        @if($eventInfo === ' ')
                            <div>&nbsp;</div>
                        @else
                            <button type="button" class="text-left" data-hs-overlay="#offcanvas-rental">
                                {{ $eventInfo }}
                            </button>
                        @endif
                    </span>
                </div>
            @else
                <div>
                    <button type="button" class="text-left w-full h-6 bg-gray-100 rounded hover:bg-gray-500 hover:shadow"
                            data-hs-overlay="#offcanvas-rental-new">&nbsp;
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>
