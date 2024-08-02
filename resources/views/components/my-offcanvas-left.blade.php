@props(['id', 'title' => '', 'class' => '', 'size' => '1', 'sizeClasses' => ''])
<?php
if($size == '1')
    $sizeClasses = 'w-100';
if($size == '2')
    $sizeClasses = 'w-screen qs:w-110';
?>
<div x-data id="{!! $id !!}" tabindex="-1"
     class="-translate-x-full overflow-scroll fixed top-0 left-0 transition-all duration-300 transform max-w-full h-full z-modal bg-white border-r {!! $class??'w-full' !!} {!! $sizeClasses !!}"
>
    <button class="absolute right-1 top-1 p-1 qs:p-2 ptr rounded hover:bg-gray-200" onclick="closeOffCanvas('{!! $id !!}');">
        <x-heroicon-o-x-mark class="w-6 h-6"/>
    </button>
    {!! $slot !!}
</div>
