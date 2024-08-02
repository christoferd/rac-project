@props(['id', 'class' => '', 'size' => '1', 'sizeClasses' => ''])
<?php
// Offcanvas Left Level 2
// - to be used above the default offcanvas-left
if($size == '1')
    $sizeClasses = 'w-100';
if($size == '2')
    $sizeClasses = 'w-screen qs:w-110';
?>
<div x-data id="{!! $id !!}" tabindex="-1"
     class="-translate-x-full overflow-scroll fixed top-0 left-0 transition-all duration-300 transform h-full z-off-canvas-lvl-2 bg-white border-r {!! $class??'w-full' !!} {!! $sizeClasses !!}"
>
    {{-- instead detect click on modal overlay x-on:click.away="log('my-off-canvas click.away...'); eventOffCanvasClickAway($event);" --}}
    <button class="fixed top-4 right-4 p-0 qs:p-2 ptr hover:bg-gray-200 rounded" onclick="closeOffCanvasSimple('{!! $id !!}');">
        <x-heroicon-o-x-mark class="w-6 h-6"/>
    </button>
    {!! $slot !!}
</div>
