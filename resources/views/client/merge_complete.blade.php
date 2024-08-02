<x-app-layout>

    <x-slot name="pageTitle">
        {!! __t('Process Complete') !!}
    </x-slot>

    <div class="max-w-260 mx-auto min-h-60 flex flex-col bg-white border shadow-sm rounded p-4 md:p-5">
        @if(isset($messages))
            <h3>{!! __('Message Log') !!}</h3>
            {!! \Illuminate\Support\Str::markdown($messages??'') !!}
        @else
            <h3>{!! __('Process Complete') !!}</h3>
        @endif
    </div>

</x-app-layout>
