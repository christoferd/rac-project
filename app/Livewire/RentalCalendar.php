<?php

namespace App\Livewire;

use App\Library\AltColoursLib;
use App\Library\DateLib;
use App\Models\Rental;
use App\Models\RentalTask;
use App\Models\Vehicle;
use App\Observers\ClientObserver;
use App\Observers\RentalObserver;
use App\Observers\TaskObserver;
use App\Observers\VehicleObserver;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class RentalCalendar extends ObjectiveComponent
{
    protected $listeners = [
        'CreatedVehicle'       => 'loadVehicles',
        'UpdatedVehicle'       => 'handleEvent_UpdatedVehicle',
        'CreatedRental'        => 'handleEvent_CreatedRental',
        'UpdatedRental'        => 'handleEvent_UpdatedRental',
        'DeletedRental'        => 'handleEvent_DeletedRental',
        'CalendarPreviousPage' => 'handleEvent_CalendarPreviousPage',
        'CalendarNextPage'     => 'handleEvent_CalendarNextPage',
        'CalendarRefresh'      => '$refresh',
        'UpdatedClient'        => 'handleEvent_UpdatedClient',
        // They can remove a client, then create a new client for an existing entry
        'CreatedClient'        => 'handleEvent_CreatedClient',
    ];

    public array $vehicles = [];
    public array $vehicleRentals = [];
    public int $dayTextsTodayIndex = (-1);
    public array $dayTexts = [];
    public array $dateTexts = [];
    public int $dateStartOffset = 0;
    public string $dateStart = '';
    public string $dateEnd = '';
    // how many days displayed in the calendar
    public int $qtyDays = 8;
    // how many days to add or subtract when they click the navigation buttons
    public int $qtyDaysShiftOnNav = 7;
    public int $mountDateOfMonth = 0;
    public int $lastRefreshedTime = 0;

    /**
     * @var string 'Today' or 'Monday'
     */
    public string $firstDaySetting = 'Monday';

    public function mount(): void
    {
        // !mountDateOfMonth must be set before calling loadCalendarData!
        $this->mountDateOfMonth = intval(date('j')); // 1 to 31

        $cToday = Carbon::today();
        // - dateStartOffset
        if($this->firstDaySetting === 'Today') {
            $this->dateStartOffset = 0;
        }
        elseif($this->firstDaySetting === 'Monday') {
            // day number: 1 (for Monday) through 7 (for Sunday)
            $this->dateStartOffset = -($cToday->format('N') - 1);
        }
        else {
            throw new \Exception('Unsupported firstDaySetting: '.$this->firstDaySetting);
        }

        $this->loadVehicles();
        $this->loadCalendarData();
    }

    public function render(): View
    {
        if(userCan('access calendar')) {
            return view('livewire.rental-calendar');
        }
        return view('app.unauthorized');
    }

    public function loadVehicles(): void
    {
        $vehicles = Vehicle::select([
                                        'id',
                                        'vehicle_make',
                                        'vehicle_model',
                                        'vehicle_kms',
                                        'vehicle_plate',
                                        'vehicle_price',
                                        // 'notes',
                                        'active'
                                    ])
                           ->where('active', 1)
                           ->orderBy('ordering')
                           ->get();
        $this->vehicles = $vehicles->keyBy('id')->toArray();
    }

    protected function loadCalendarData(): void
    {
        $this->lastRefreshedTime = time();

        $debugInfo = false;
        $debugDumps = false;
        $debugDumpsRentalId = 13;
        $colours = new AltColoursLib(config('rental.calendar_colours')[0]);
        $colours2 = new AltColoursLib(config('rental.calendar_colours')[1]);

        // Check still on the same day
        if($this->mountDateOfMonth !== intval(date('j'))) {
            $this->mount();
            return;
        }

        $this->vehicleRentals = [];
        $this->dayTexts = [];
        $this->dateTexts = [];
        Carbon::setLocale(config('app.locale'));
        $timeSlots = collect(config('rental.calendar_entry_time_slots'));
        $timeSlotsValues = $timeSlots->pluck('value');

        // Config dates
        $cStart = Carbon::today()->addDays($this->dateStartOffset)->startOfDay();
        $cEnd = Carbon::today()->addDays($this->dateStartOffset + ($this->qtyDays - 1))->endOfDay();
        $this->dateStart = $cStart->toDateString();
        $this->dateEnd = $cEnd->toDateString();
        $percentPerDay = round(100 / $this->qtyDays, 4);
        // add 1 to the timeSlot count so that the last timeslot doesn't start on the border
        $percentPerTimeSlot = round($percentPerDay / ($timeSlots->count() + 1), 4);

        if($debugDumps) {
            dump('## Calendar Settings ##');
            dump('$cStart: '.$cStart);
            dump('$cEnd: '.$cEnd);
            dump('qtyDays: '.$this->qtyDays);
            dump('$percentPerDay: '.$percentPerDay);
            dump('timeSlots->count(): '.$timeSlots->count());
            dump('$percentPerTimeSlot: '.$percentPerTimeSlot);
            dump('--------');
        }

        // X day text
        $cText = clone $cStart;
        $this->reset('dayTextsTodayIndex');
        for($i = 0; $i < $this->qtyDays; $i++) {
            $this->dayTexts[] = $cText->translatedFormat('l');
            $this->dateTexts[] = $cText->translatedFormat('j F');
            if($cText->isToday()) {
                $this->dayTextsTodayIndex = $i;
            }
            $cText->addDay();
        }

        // Rentals of each Vehicle
        foreach($this->vehicles as $vehiclesIndex => $v) {

            $vehicleId = $v['id'];
            // Rentals of the Vehicle
            $rentals = Rental::with(['client:id,name'])
                             ->where('vehicle_id', $vehicleId)
                             ->where(function(\Illuminate\Database\Eloquent\Builder $query) use ($cStart, $cEnd) {
                                 $dateFrom = $cStart->toDateString();
                                 $dateTo = $cEnd->toDateString();
                                 $sql = sprintf(
                                 // start or ends within the dates
                                     '( '.
                                     '   ( (date_collect BETWEEN "%s" AND "%s") OR (date_return BETWEEN "%s" AND "%s") ) '.
                                     //  also get rental that starts before these dates and ends after these dates,
                                     //  essentially occupying the whole section
                                     '   OR ( date_collect < "%s" AND date_return > "%s") '.
                                     ') '
                                     ,
                                     $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo
                                 );
                                 return $query->whereRaw($sql);
                             })
                             ->orderBy('date_collect', 'ASC')
                             ->orderBy('time_collect', 'ASC')
                             ->get();

            /**
             * Calculated vehicle rentals data for the component
             */
            $vehicleRentals = [];
            $rentalDataPrev = [];

            foreach($rentals as $rental) {

                $cRentalStart = Carbon::parse($rental->date_collect.' '.$rental->time_collect);
                $cRentalEnd = Carbon::parse($rental->date_return.' '.$rental->time_return);

                /*
                 * Diff
                 */
                // Does rental start before calendar?
                $startDiffDays = DateLib::diffInDaysOfDateOnly($cStart, $cRentalStart);
                $endDiffDays = DateLib::diffInDaysOfDateOnly($cStart, $cRentalEnd);

                $startHoursMins = $cRentalStart->format('H:i:00');
                $endHoursMins = $cRentalEnd->format('H:i:00');
                $timeSlotPosition = $timeSlotsValues->search($startHoursMins);
                $percentStart = ($startDiffDays * $percentPerDay + (($timeSlotPosition ?: 0) * $percentPerTimeSlot));
                // "+ 1" so that when it ends on 0700, it doesn't end on the border
                $timeSlotPositionEnd = $timeSlotsValues->search($endHoursMins);
                $percentEnd = ($endDiffDays * $percentPerDay + ((($timeSlotPositionEnd ?: 0) + 1) * $percentPerTimeSlot));
                if(($vehiclesIndex % 2) === 0 || $vehiclesIndex == 0) {
                    $colour = $colours->getNextString();
                }
                else {
                    $colour = $colours2->getNextString();
                }

                $rentalData = [
                    'rental_id'     => $rental->id,
                    'info'          => $rental->client->name
                                       // Debug
                                       .($debugInfo && app()->isLocal() ? " ({$rental->id}) " : ''),
                    'percent_start' => round($percentStart, 4),
                    'percent_end'   => round($percentEnd, 4),
                    'percent_total' => round($percentEnd - $percentStart, 4),
                    'colour'        => $colour,

                    'rental_date_collect' => $rental->date_collect,
                    'rental_date_return'  => $rental->date_return,
                    'start_time'          => \substr($rental->time_collect, 0, 5),
                    'end_time'            => \substr($rental->time_return, 0, 5),
                    'start_diff_days'     => $startDiffDays,
                    'end_diff_days'       => $endDiffDays,

                    'cRentalStart' => $cRentalStart->toDateTimeString(),
                    'cRentalEnd'   => $cRentalEnd->toDateTimeString(),
                    'cStart'       => $cStart->toDateTimeString(),
                    'cEnd'         => $cEnd->toDateTimeString(),
                ];

                if($debugDumps && $rental->id == $debugDumpsRentalId) {
                    dump('## Rental '.$rental->id);
                    dump('start $timeSlotPosition: '.$timeSlotPosition);
                    dump('$startDiffDays: '.$startDiffDays);
                    dump('$percentStart: '.$percentStart);
                    dump('end $timeSlotPositionEnd: '.$timeSlotPositionEnd);
                    dump('$endDiffDays: '.$endDiffDays);
                    dump('$percentEnd: '.$percentEnd);
                    dump($rentalData);
                }

                $vehicleRentals[] = $rentalData;
                $rentalDataPrev = $rentalData;
            }

            $this->vehicleRentals[$vehicleId] = $vehicleRentals;
        }
    }

    public function handleEvent_CreatedRental(int $id): void
    {
        $this->loadCalendarData();
    }

    public function handleEvent_UpdatedRental(int $id): void
    {
        $this->loadCalendarData();
    }

    public function handleEvent_DeletedRental(int $id): void
    {
        $this->loadCalendarData();
    }

    public function handleEvent_UpdatedClient(int $id): void
    {
        $this->loadCalendarData();
    }

    public function handleEvent_CreatedClient(int $id): void
    {
        $this->loadCalendarData();
    }

    public function clickedCalendarSpace(int $vehicleId, int $calColumn): void
    {
        $c = Carbon::createFromFormat('Y-m-d', $this->dateStart)->addDays($calColumn);
        // $this->alertDebug("clickedCalendarSpace v($vehicleId) col($calColumn) date: ".$c->toDateString());
        $this->dispatch('CreateRentalWithVehicleDate', vehicle_id: $vehicleId, mysqlDate: $c->toDateString());
    }

    public function handleEvent_CalendarPreviousPage(): void
    {
        $this->dateStartOffset -= $this->qtyDaysShiftOnNav;
        $this->loadCalendarData();
    }

    public function handleEvent_CalendarNextPage(): void
    {
        $this->dateStartOffset += $this->qtyDaysShiftOnNav;
        $this->loadCalendarData();
    }

    public function handleEvent_UpdatedVehicle(int $id): void
    {
        $this->loadVehicles();
        $this->loadCalendarData();
    }

    public function refreshDataIfRequired(): void
    {
        if(
            ClientObserver::checkTimestampTableChangedSince($this->lastRefreshedTime)
            || VehicleObserver::checkTimestampTableChangedSince($this->lastRefreshedTime)
            || TaskObserver::checkTimestampTableChangedSince($this->lastRefreshedTime)
            || RentalObserver::checkTimestampTableChangedSince($this->lastRefreshedTime)
        )
        {
            // $this->alertDebug('loadCalendarData because data has changed since '.date('d/m/Y - H:i:s', $this->lastRefreshedTime));
            $this->loadCalendarData();
        }
    }
}
