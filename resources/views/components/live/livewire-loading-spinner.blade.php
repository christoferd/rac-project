@props(['id' => '', 'cssSize' => 'h-10 w-10', 'cssDisplay' => 'sticky', 'cssPosition' => 'top-4 left-6', 'cssSpinner' => ''])
{{--Livewire Loading Spinner from preline--}}
<div id="{!! $id !!}"
     wire:loading.block
     class="hidden h-0 w-4 overflow-visible z-loading bg-transparent {!! $cssDisplay !!} {!! $cssPosition !!}" style="height: 0!important;"
><x-app.loading-spinner loading-spinner-class="{!! $cssSize.' '.$cssSpinner !!}" /></div>
