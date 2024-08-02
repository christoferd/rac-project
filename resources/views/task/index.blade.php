<x-app-layout>

    <x-slot name="pageTitle">{!! __('Tasks') !!}</x-slot>

    <div class="flex justify-between">
        <div>&nbsp;</div>
        <button class="btn"
                onclick="Livewire.dispatch('ClickedCreateTask'); openOffCanvas('TaskEditor')"
        >
            <x-heroicon-o-plus-small class="w-5 h-5"/>
            {{--style to fix centering issue--}}
            <span style="line-height: 17px">{!! __('New') !!}</span>
        </button>
    </div>

    <livewire:tasks-table/>

    <x-my-offcanvas-left id="TaskEditor">
        <livewire:task-editor/>
    </x-my-offcanvas-left>

</x-app-layout>
