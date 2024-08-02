<div>
    <div wire:offline class="fixed top-0 left-0 w-full h-screen z-critical bg-transparent">
        <div id="DiscMsg" class="max-w-xl h-20 m-auto border rounded bg-orange-200 disconnected-message">
            <div class="h-20 p-3 flex gap-3 items-center justify-center">
                <x-heroicon-c-exclamation-triangle class="w-8 h-8 text-orange-400"/>
                <h3 class="">{!! __('This device is currently offline. Connection to the internet has been interrupted.') !!}</h3>
            </div>
        </div>
    </div>
</div>
