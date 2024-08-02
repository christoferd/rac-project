{{--
WARNING: This only works from within a livewire component!!!
--}}
<div id="OfflineMessage" class="hs-overlay w-full h-full fixed top-0 left-0 z-[9999] overflow-x-hidden overflow-y-auto"
     wire:offline
     style="background-color: rgba(0,0,0,0.5)">
    <div class="opacity-100 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-md shadow-red-300 rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
            <div class="flex justify-start gap-4 items-center py-3 px-4 border-b dark:border-gray-700">
                <x-heroicon-c-exclamation-triangle class="w-8 h-8 text-rose-700 "/>
                <h3 class="font-bold text-rose-700 dark:text-white">
                    {!! __('Offline') !!}
                </h3>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="mt-1 text-gray-800 dark:text-gray-400">
                    {!! __('It appears that your device is not connected to the internet, or lost connection to our server.') !!}
                </p>
            </div>
        </div>
    </div>
</div>
