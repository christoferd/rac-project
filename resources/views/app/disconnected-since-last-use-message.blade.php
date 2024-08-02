<div id="DiscSLUM" class="hidden">
    <div class="bg-amber-400 text-black font-normal py-3 px-6 text-center flex items-center gap-6">
        <div>
            {!! __('This device has been offline since last time the application was used. Recommend refreshing the page.') !!}
        </div>
        <x-button class="!bg-amber-100 !text-black !border-amber-200"
                  onclick="window.location.reload()"
        >
            <div class="flex items-center gap-3">
                <x-heroicon-o-arrow-path class="w-8 h-8"/>
                <span>{!! __('Refresh the page') !!}</span>
            </div>
        </x-button>
    </div>
</div>
