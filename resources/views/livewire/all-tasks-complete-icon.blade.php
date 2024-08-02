<div>
    @if($allTasksComplete)
        <x-heroicon-o-check class="{!! $cssClass !!}"/>
    @else
        <x-heroicon-o-x-mark class="{!! $cssClass !!}"/>
    @endif
</div>
