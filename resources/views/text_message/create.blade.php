<x-app-layout>

    <x-slot name="header_2_col">
        <h2>
            {!! __('Create') !!}: {!! __('Message') !!}
        </h2>
        <div>
            {{--action buttons--}}
            <x-link-button href="{!! route('text-messages.index') !!}" icon-left="list">
                {!! __('Messages') !!}</x-link-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 sm:px-4">
            <livewire:text-message-create-edit text-message-id="0"/>
        </div>
    </div>

</x-app-layout>
