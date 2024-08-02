<div class="sticky h-0 overflow-visible top-4 w-30 mx-auto">

    {{--Message Modified--}}
    {{--    <span class="px-2 py-1 inline-block">&nbsp;</span>--}}
    {{--    <div x-ref="xRentalEditorModified"--}}
    {{--         x-show="dirty===true && !dataSaved"--}}
    {{--         class="inline-block bg-orange-50 rounded-sm px-2 py-1 text-orange-500">--}}
    {{--        <x-heroicon-o-exclamation-triangle class="h-4 w-4 inline"/>--}}
    {{--        Modificado--}}
    {{--    </div>--}}
    {{--         x-show="dataSaved"--}}

    {{--Message Saved--}}
    <div x-ref="xDataSavedMessage"
         x-bind:class="dataSaved ? 'show' : ''"
         class="data-saved-message bg-emerald-50 rounded-sm px-2 py-1 text-emerald-700"
    >
        <x-heroicon-o-check class="h-4 w-4 inline"/>
        {!! __('Saved') !!}
    </div>
</div>
