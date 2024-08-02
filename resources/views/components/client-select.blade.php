<div>
    <select {!! $attributes !!}
        onchange="log('ClientSelect option changed to: ' + this.value); log('Livewire dispatch: SelectedClient_{!! $targetComponent !!}'); Livewire.dispatch('SelectedClient_{!! $targetComponent !!}', {id: this.value?this.value:0});">
        <option value="0">{!! __('Clients') !!}...</option>
        @foreach($clients as $client)
            <option value="{!! $client->id !!}">{{ $client->name }}</option>
        @endforeach
    </select>
</div>
