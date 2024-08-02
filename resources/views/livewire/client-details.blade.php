<div>
    @if(!empty($clientArray))
    <div class="flex flex-col gap-2 py-2 transition ease-in-out duration-150 cursor-pointer">
        <div class="flex items-center justify-between leading-tight">
            <div class="font-semibold">
                {{ $clientArray['name']?:'-' }}
            </div>
            <div class="text-gray-600 flex items-center gap-1 whitespace-no-wrap w-12">
                <x-heroicon-m-star class="w-4 h-4 text-gray-300"/>
                <span>{{ $clientArray['rating']<0?'':$clientArray['rating'] }}</span>
            </div>
        </div>
        <div class="flex items-center justify-between leading-tight">
            <div class="">
                {{ $clientArray['address']?:'-' }}
            </div>
            <div class="text-gray-600">
                {{ $clientArray['phone_number']?:'-' }}
            </div>
        </div>
        <div>
            @if($clientId)
                <x-client.client-action-buttons :client-id="$clientId" :phone-number="$clientArray['phone_number']"
                                                :allow-remove="$allowRemove" :allow-search="$allowSearch" :allow-edit="$allowEdit"
                                                :allow-view-rentals="$allowViewRentals"
                                                class="flex gap-2 justify-end"/>
            @endif
        </div>
        <div class="text-sm">
            <div class="text-gray-500 text-xs">
                {!! __('Notes') !!}
            </div>
            <div>
                {{ $clientArray['notes'] }}
            </div>
        </div>
    </div>
    @else
        <h3>{!! __('Loading') !!}</h3>
    @endif
</div>
