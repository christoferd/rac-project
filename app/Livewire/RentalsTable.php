<?php

namespace App\Livewire;

use App\Library\DateLib;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class RentalsTable extends ObjectiveComponent
{
    use WithPagination;

    protected $listeners = [
        // Client
        'UpdatedClient'                => 'loadData',
        // Rental
        'CreatedRental'                => 'loadData',
        'UpdatedRental'                => 'loadData',
        'DeletedRental'                => 'loadData',
        // Dispatched from JS
        'ClosedOffcanvasPanel'         => 'loadData',
        // Selected a Client from filter component
        'SelectedClient'               => 'handleEvent_SelectedClient',
        // selected a Vehicle from dropdown list in the form
        'SelectedVehicle_RentalsTable' => 'handleEvent_SelectedVehicle',
        // selected a date using the date picker
        'SelectedDate_RentalsTable'    => 'handleEvent_SelectedDate',
    ];

    /**
     * @var string MySQL formatted Date
     */
    public string $dateToday = '0000-00-00';

    public string $orderKey = 'date_collect';
    public string $orderDirection = 'asc';
    public array $orderByOptions = [];

    public array $perPageOptions = [
        '10' => '10', '25' => '25', '50' => '50', '100' => '100', '150' => '150', '200' => '200', '250' => '250', '500' => '500',
    ];

    public int $perPage = 10;

    public bool $showSearchBox = false;
    public bool $showButtonNew = false;
    public string $searchString = '';
    public int $paginationPerPage = 0;
    public array $selectableDates = [];

    // Filters
    public int $filterClientId = 0;
    public int $filterVehicleId = 0;
    // mysql date
    public string $filterDate = '';

    /**
     * @var LengthAwarePaginator|null
     */
    protected LengthAwarePaginator|null $rentals = null;

    function mount(): void
    {
        $this->dateToday = Carbon::today()->toDateString();
        // Default Date
        if(empty($this->filterDate)) {
            $this->filterDate = $this->dateToday;
        }
        // Default Sort/Order

        $this->orderByOptions = [
            ['key'       => 'client_name',
             'label'     => 'Cliente',
             'direction' => 'asc'
            ],
            ['key'       => 'vehicle_name',
             'label'     => 'Vehículo',
             'direction' => 'asc'
            ],
            ['key'       => 'date_collect',
             'label'     => 'Retiro',
             'direction' => 'asc'
            ],
            ['key'       => 'date_collect',
             'label'     => 'Retiro al revés',
             'direction' => 'desc'
            ],
            ['key'       => 'date_return',
             'label'     => 'Retorno',
             'direction' => 'asc'
            ],
            ['key'       => 'date_return',
             'label'     => 'Retorno al revés',
             'direction' => 'desc'
            ],
            ['key'       => 'price_total',
             'label'     => 'Total menor a mayor',
             'direction' => 'asc'
            ],
            ['key'       => 'price_total',
             'label'     => 'Total mayor a menor',
             'direction' => 'desc'
            ],
        ];
    }

    public function loadData(): void
    {
        $q = Rental::with(['client', 'vehicle'])
                   ->select('rentals.*')
                   ->where('date_collect', '<=', $this->dateToday);
        /*
         * Filters
         */
        // Filter Client
        if($this->filterClientId) {
            $q->whereClientId($this->filterClientId);
        }

        // Filter Vehicle
        if($this->filterVehicleId) {
            $q->whereVehicleId($this->filterVehicleId);
        }

        // Filter Date
        if(!empty($this->filterDate)) {
            $q->where(function($query) {
                $query->where('date_collect', '=', $this->filterDate)
                      ->orWhere('date_return', '=', $this->filterDate);
            });
        }

        // Order
        if($this->orderKey === 'client_name') {
            $q->join('clients', 'client_id', '=', 'clients.id')
              ->orderBy('clients.name', $this->orderDirection);
        }
        elseif($this->orderKey === 'vehicle_name') {
            $q->join('vehicles', 'vehicle_id', '=', 'vehicles.id')
              ->orderBy('vehicles.vehicle_make', $this->orderDirection)
              ->orderBy('vehicles.vehicle_model', $this->orderDirection);
        }
        elseif($this->orderKey === 'price_total') {
            $q->orderBy($this->orderKey, $this->orderDirection);
        }
        // When selecting the date, also sort by the time
        elseif($this->orderKey === 'date_collect') {
            $q->orderBy('date_collect', $this->orderDirection)
              ->orderBy('time_collect', $this->orderDirection);
        }
        // When selecting the date, also sort by the time
        elseif($this->orderKey === 'date_return') {
            $q->orderBy('date_return', $this->orderDirection)
              ->orderBy('time_return', $this->orderDirection);
        }

        $this->rentals = $q->paginate($this->perPage);
    }

    public function render(): View
    {
        if(userCan('access rentals')) {
            $this->loadData();
            return view('livewire.rentals-table', ['rentals' => $this->rentals]);
        }
        return view('app.unauthorized');
    }

    public function handleEvent_SelectedClient(int $id): void
    {
        $this->filterClientId = $id;
        // Reset pagination
        $this->resetPage();
    }

    public function handleEvent_SelectedVehicle(int $id): void
    {
        $this->filterVehicleId = $id;
        // Reset pagination
        $this->resetPage();
    }

    public function handleEvent_SelectedDate(string $mysqlDate): void
    {
        // allow clearing/reset date
        if($mysqlDate === '') {
            $this->reset('filterDate');
            return;
        }
        elseif(!DateLib::isMysqlDate($mysqlDate)) {
            $this->alertError(\ucwords(__('invalid format')));
            $this->reset('filterDate');
            return;
        }
        $this->filterDate = $mysqlDate;
        // Reset pagination
        $this->resetPage();
    }

    public function setOrdering(string $orderKey, string $orderDirection): void
    {
        $this->orderKey = $orderKey;
        $this->orderDirection = $orderDirection;
        // Reset pagination
        $this->resetPage();
    }

}
