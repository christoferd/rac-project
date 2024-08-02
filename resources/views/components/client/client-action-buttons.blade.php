@props(['clientId' => 0, 'phoneNumber' => '', 'allowRemove' => 0, 'allowSearch' => 0, 'allowEdit' => 0, 'allowViewRentals' => 0,
'class' => 'flex gap-2 justify-end'])
<div {{ $attributes->class($class) }}>
    {{-- Button - Client Whatsapp         --}}
    <x-button.phone-call-link-icon size="4" phone-number="{{ $phoneNumber }}" class="btn-icon-only !m-0"/>
    <x-button.whatsapp-link-icon size="4" class="btn-icon-only !m-0" client-id="{!! $clientId !!}"/>
    @if($clientId)
        {{-- Button - Client Rentals History         --}}
        @if($allowEdit)
            <button class="btn-icon-only !m-0"
                    onclick="log('! dispatch ClickedClientRentals {!! $clientId !!}'); Livewire.dispatch('ClickedClientRentals', [{!! $clientId !!}]); openOffCanvasSimple('ClientRentals')">
                <x-vaadin-car/>
            </button>
        @endif
        {{-- Button - Edit Client         --}}
        @if($allowEdit)
            <button class="btn-icon-only !m-0"
                    onclick="log('! dispatch ClickedEditClient'); Livewire.dispatch('ClickedEditClient', [{!! $clientId !!}]); openOffCanvasSimple('ClientEditor')">
                <x-heroicon-o-pencil/>
            </button>
        @endif
        {{-- Button - Remove Client         --}}
        @if($allowRemove)
            <button class="btn-icon-only !m-0"
                    onclick="log('! dispatch RemoveClient'); Livewire.dispatch('RemoveClient')">
                <x-heroicon-o-x-mark/>
            </button>
        @endif
    @endif
    {{-- Button - Client Search         --}}
    @if($allowSearch)
        <button class="h-8 w-8 border border-gray-300 rounded hover:border-gray-700 hover:shadow-lg !m-0"
                onclick="log('! dispatch ClickedSearchClient'); Livewire.dispatch('ClickedSearchClient')">
            <x-heroicon-o-magnifying-glass class="m-auto w-5 h-5 text-gray-800"/>
        </button>
    @endif
</div>
