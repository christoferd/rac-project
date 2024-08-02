<div class="flex w-full align-top flex-wrap">
    <h1>



        client-images-display???



    </h1>
    @if($media !== null)
        @foreach($media as $mediaItem)
            <div class="w-1/{{$numColumns}}">
                <button
                    onclick="openMyModal('ImageGalleryHammer'); ig_loadImage('{!! $mediaItem->getUrl() !!}')"
                    class="cursor-pointer"
                >
                    <img src="{!! $mediaItem->getUrl('thumb_160') !!}" alt="image"/>
                </button>
            </div>
        @endforeach
    @else
        <div class="flex-grow pt-2 pr-3">0 imágenes</div>
    @endif
{{--    @if($clientImages)--}}
{{--        @foreach($clientImages as $relativePath)--}}
{{--            <div class="w-1/{{$numColumns}}">--}}
{{--                <button--}}
{{--                    onclick="openMyModal('ImageGalleryHammer'); ig_loadImage('{!! \App\Library\FileLib::loadFileUrl('admin', '', $relativePath) !!}')"--}}
{{--                    class="cursor-pointer"--}}
{{--                >--}}
{{--                    <x-app.img-file-load disk="admin" :filename="$relativePath" thumb="1"/>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    @else--}}
{{--        <div class="flex-grow pt-2 pr-3">0 imágenes</div>--}}
{{--    @endif--}}
    @if($allowEdit)
        <div x-data class="border-none text-gray-700 w-1/4 flex items-center">
            <button class="btn-icon-only"
                    x-on:click="$nextTick(() => { Livewire.dispatch('ManageImagesForClient', [ {{ $clientId }} ]); openMyModal('ClientImagesManager'); });">
                <x-heroicon-o-pencil-square class="w-4 h-4 text-gray-700"/>
            </button>
        </div>
    @endif

</div>
