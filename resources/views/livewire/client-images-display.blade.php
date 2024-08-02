<div x-data="{
    confirmDelete: () => { console.log('confirm delete'); }
    }">
    {{--    relativePath: '',--}}
    {{--//disk: @ entangle('disk').live,--}}
    <div class="relative top-0 left-0">
        <x-live.livewire-loading-spinner/>
    </div>

    <h1>







        Not Used // Chris D. 11-Apr-2024







    </h1>

    <div wire:loading.remove>
        <div class="flex flex-wrap w-full align-top">
            @if(!empty($media))
                {{--$i is just a basic index number location in the array--}}
                @foreach($media as $m)
                    <div style="width: 118px; height: 148px;" class="p-1">
                        {{-- // Chris D. 6-Mar-2024 - can't open another top modal when this modal is open --}}
                        <div
                            wire:ignore
                            onclick="if(openMyModal('ImageGalleryHammer')) { ig_loadImage('{!! $m->getUrl() !!}'); }"
                            class="w-full h-full overflow-hidden bg-contain bg-no-repeat bg-center cursor-pointer"
                            style="background-image: url('{!! $m->getUrl('thumb_160') !!}');"
                        ></div>
                        @if($showDeleteButton)
                            <div class="flex items-center justify-start gap-1">
                                    <button class="text-gray-500 hover:text-rose-700 ptr p-1"
                                            x-on:click="relativePath = '{!! $relativePath !!}'; $refs.confirm{{ $m->id }}.toggleClass('hidden')"
                                    >
                                        <x-heroicon-o-trash class="w-4 h-4"/>
                                    </button>
                                    <button class="hidden text-sm text-rose-700"
                                            x-ref="confirm{!! $m->id !!}"
                                            value="{!! $m->id !!}"
                                            x-on:click="Livewire.dispatch('DeleteClientImage', [$el.value])"
                                    >
                                        <div class="flex gap-1">
                                            {!! __t('Confirm', 'Delete', 'File') !!}
                                            <x-heroicon-o-chevron-right class="w-4 h-4"/>
                                            <x-heroicon-o-trash class="w-4 h-4"/>
                                        </div>
                                    </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="flex-grow pt-2 pr-3">0 imágenes</div>
            @endif
        </div>
    </div>
</div>
