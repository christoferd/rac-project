<x-app-layout>

    <x-slot name="header_2_col">
        <h2>
            {!! __('Edit') !!}: {!! __('Message') !!}
        </h2>
        <div>
            {{--action buttons--}}
            <x-link-button href="{!! route('text-messages.index', $textMessageId) !!}" icon-left="list">
                {!! __('Messages') !!}</x-link-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:text-message-create-edit text-message-id="{!! $textMessageId !!}"/>
        </div>
    </div>

</x-app-layout>
