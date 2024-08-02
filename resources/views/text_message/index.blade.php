<x-app-layout>

    <x-slot name="pageTitle">{!! __('Messages') !!}</x-slot>

    <div class="flex justify-between">
        <div>&nbsp;</div>
        <button class="btn"
                onclick="Livewire.dispatch('ClickedCreateTextMessage'); openOffCanvas('TextMessageEditor')"
        >
            <x-heroicon-o-plus-small class="w-5 h-5"/>
            {{--style to fix centering issue--}}
            <span style="line-height: 17px">{!! __('New') !!}</span>
        </button>
    </div>

    <livewire:text-messages-table/>

    <x-my-offcanvas-left id="TextMessageEditor">
        <livewire:text-message-editor/>
    </x-my-offcanvas-left>

</x-app-layout>
