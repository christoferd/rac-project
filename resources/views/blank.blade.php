<x-app-layout>

    <x-slot name="pageTitle">{!! $pageTitle??'Blank - No $pageTitle' !!}</x-slot>

    @if(isset($messages))
        <h3>{!! __('Messages') !!}</h3>
        <?php if(is_array($messages)) { $messages = implode("\n\n", $messages); } ?>
        @if(is_string($messages))
            {!! \Illuminate\Support\Str::markdown($messages) !!}
        @else
            <p>$messages is the wrong type: {!! gettype($messages) !!}</p>
        @endif
    @endif

</x-app-layout>
