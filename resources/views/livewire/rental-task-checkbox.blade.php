<div>
    <div class="flex items-start gap-3">
        <div class="flex-shrink">
            <div wire:loading.remove wire:target="checkboxClicked">
                <input type="checkbox"
                       wire:click="checkboxClicked({!! $taskId !!})" {!! $completed?'checked':'' !!}/>
            </div>
            <div wire:loading wire:target="checkboxClicked">
                <x-app.loading-spinner loading-spinner-class="h-4 w-4 relative top-0.5"/>
            </div>
        </div>
        <div class="flex-grow">
            <div>
                {{ $title }}
            </div>
            <div class="text-gray-500 text-sm">
                @if($completed)
                    {{ $userCompletedName }}
                    &middot; {!! $dateTimeCompleted !!}
                @endif
            </div>
        </div>
    </div>
</div>
