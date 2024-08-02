<x-view-image-layout>
    @if(isset($message))
        <p>{!! $message !!}</p>
    @endif
    <img src="{!! $url !!}" alt="image">
</x-view-image-layout>
