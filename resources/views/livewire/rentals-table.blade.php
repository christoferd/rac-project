<div>
    {{--
    Offline Message
    --}}
    @include('app.offline-message')

    <x-live.livewire-loading-spinner/>

    {{--
    Filters
    --}}
    <div class="no-print">
        @include('rental.index-filters')
    </div>

    {{--
    Actions Bar
    --}}
    <div class="flex gap-2 items-center">
        {{--Left--}}
        <div class="flex-1">
        </div>
        {{--Right--}}
        <div class="no-print flex-1 text-right">
        </div>
    </div>

    {{--
    Data Table
    --}}
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                    @if($rentals)
                        {{--
                        Small Screens
                        --}}
                        <div class="block qs:hidden">
                            <div class="flex-column gap-1">
                                @foreach($rentals as $rental)
                                    <div class="border-b pb-2 pt-2 flex leading-5"
                                         onclick="log('! dispatch ClickedEditRental'); Livewire.dispatch('ClickedEditRental', [{!! $rental->id !!}]); openOffCanvas('RentalEditor')"
                                    >
                                        <div class="w-14">
                                            <div class="{!! ($rental->date_collect === $dateToday?'font-bold':'') !!} flex flex-col items-center"
                                            >{!! DateLib::mysqlDateToFormat($rental->date_collect,'d/m') !!}
                                                <br>{!! substr($rental->time_collect,0,5) !!}<br>
                                                <div>
                                                    @if(\App\Models\RentalTask::hasCompletedAllTasks($rental, 1))
                                                        <x-heroicon-o-check class="w-5 h-5"/>
                                                    @else
                                                        <x-heroicon-o-x-mark class="w-5 h-5"/>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="">
                                                {{ $rental->vehicle->vehicle_make }} &middot; {{ $rental->vehicle->vehicle_model }}
                                            </div>
                                            <div class="flex flex-row">
                                                <div class="w-20">{{ $rental->vehicle->vehicle_plate }}</div>
                                                <div class="flex-grow text-right">{{ $rental->days_to_charge }}</div>
                                                <div class="w-20 text-right">${{ nf0($rental->price_total) }}</div>
                                            </div>
                                            <div class="">
                                                <div wire:click.stop="$dispatch('ClickedEditClient', [{!! $rental->client->id !!}])"
                                                     onclick="openOffCanvas('ClientEditor')">
                                                    {{ $rental->client->name }}
                                                </div>
                                            </div>
                                            @if($rental->notes)
                                                <div class="pl-10 text-gray-500">
                                                    Notas: {{ $rental->notes }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="w-14 text-right">
                                            <div class="{!! ($rental->date_return === $dateToday?'font-bold':'') !!} text-center">
                                                {!! DateLib::mysqlDateToFormat($rental->date_return,'d/m') !!}<br>
                                                {!! substr($rental->time_return,0,5) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {{--
                        Medium Screens +
                        --}}
                        <table class="hidden qs:block min-w-full">
                            <thead>
                            <tr>
                                <th scope="col" class="no-print text-left"></th>
                                <th scope="col" class="{!! ($orderKey === 'client_name'?'font-extrabold':'') !!}">
                                    {!! ucfirst(__(Rental::label('client_id'))) !!}
                                </th>
                                <th scope="col" class="{!! ($orderKey === 'vehicle_name'?'font-extrabold':'') !!}">
                                    {!! ucfirst(__(Rental::label('vehicle_id'))) !!}
                                </th>
                                <th scope="col" class="">{!! __('Collect') !!}</th>
                                <th scope="col" class=""></th>
                                <th scope="col" class="" x-tooltip.raw="{!! __('All tasks completed?') !!}">
                                    <x-heroicon-o-list-bullet class="h-5 w-5"/>
                                </th>
                                <th scope="col" class="">{!! __('Return') !!}</th>
                                <th scope="col" class=""></th>
                                <th scope="col" class="text-right">{!! ucfirst(__(Rental::label('price_total'))) !!}</th>
                                <th scope="col" class="text-center">{!! ucfirst(__(Rental::label('notes'))) !!}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rentals as $rental)
                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-200">
                                    <td class="no-print">
                                        <div class="flex gap-2 items-center">
                                            <div class="ptr"
                                                 onclick="Livewire.dispatch('ClickedEditRental', [{!! $rental->id !!}]); openOffCanvas('RentalEditor')">
                                                <x-heroicon-o-pencil-square class="w-5 h-5 text-gray-700"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td wire:click.stop="$dispatch('ClickedEditClient', [{!! $rental->client->id !!}])"
                                        onclick="openOffCanvas('ClientEditor')" class="ptr">
                                        {{ $rental->client->name }}
                                    </td>
                                    <td class="">
                                        {{ $rental->vehicle->vehicle_make }} &middot; {{ $rental->vehicle->vehicle_model }}<br>
                                        {{ $rental->vehicle->vehicle_plate }}
                                    </td>
                                    <td class="{!! ($rental->date_collect === $dateToday?'font-bold':'') !!}">
                                        {!! DateLib::mysqlDateToHuman($rental->date_collect) !!}
                                    </td>
                                    <td class="{!! ($rental->date_collect === $dateToday?'font-bold':'') !!}">
                                        {!! substr($rental->time_collect,0,5) !!}
                                    </td>
                                    <td>
                                        @if(\App\Models\RentalTask::hasCompletedAllTasks($rental, 1))
                                            <x-heroicon-o-check class="w-5 h-5"/>
                                        @else
                                            <x-heroicon-o-x-mark class="w-5 h-5"/>
                                        @endif
                                    </td>
                                    <td class="{!! ($rental->date_return === $dateToday?'font-bold':'') !!}">
                                        {!! DateLib::mysqlDateToHuman($rental->date_return) !!}
                                    </td>
                                    <td class="{!! ($rental->date_return === $dateToday?'font-bold':'') !!}">
                                        {!! substr($rental->time_return,0,5) !!}
                                    </td>
                                    <td class="text-right">${{ nf0($rental->price_total) }}</td>
                                    <td class="!whitespace-normal">{{ $rental->notes }}</td>
                                </tr>
                            @endforeach
                            <tr class="no-print">
                                <td colspan="9">
                                    {!! $rentals->links('livewire.pagination.simple-tailwind'); !!}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <div class="text-lg text-center my-6">{!! __('No results') !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
