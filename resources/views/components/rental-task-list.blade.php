<div class="flex flex-col gap-1 py-1">
    @foreach($tasks as $task)
        <div>
            {{--https://livewire.laravel.com/docs/components#adding-wirekey-to-foreach-loops--}}
            {{--https://livewire.laravel.com/docs/nesting#rendering-children-in-a-loop--}}
            {{--https://laracasts.com/discuss/channels/livewire/uncaught-snapshot-missing-on-livewire-component-with-id-1--}}
            <livewire:rental-task-checkbox wire:key="RT-Check-{!! $task['rental_id'] !!}-{!! $task['id'] !!}" :rental-id="$task['rental_id']"
                                           :task-id="$task['id']"/>
        </div>
    @endforeach
</div>
