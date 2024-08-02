<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Library\DateLib;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;

class RentalCreator extends ObjectiveComponent
{
    use DataModifiedSaved;

    /**
     * So that the component knows when the record is successfully created
     */
    public $rental_id = 0;
    public $rental_client_id = 0;
    public $rental_vehicle_id = 0;
    public $rental_date_collect = '';
    public $rental_time_collect = '';
    public $rental_date_return = '';
    public $rental_time_return = '';
    public $rental_price_day = 0;
    public $rental_days_to_charge = 0;
    public $rental_price_total = 0;
    public $rental_notes = '';

    public $client_name = '';
    public $client_address = '';
    public $client_phone_number = '';
    public $client_notes = '';
    public $client_rating = -1;

    // Relational Data
    /**
     * @var Vehicle
     */
    public $vehicle = null;

    // protected $listeners = [
    //     // selected a Client from dropdown list in the form
    //     'SelectedClient_RentalCreator' => 'handleEvent_SelectedClient',
    //     // Notified by another component
    //     'DateSelected_RentalCreator'   => 'handleEvent_DateSelected',
    //     // by RentalCalendar
    //     'CreateRentalWithVehicleDate'  => 'handleEvent_CreateRentalWithVehicleDate',
    //     // --
    //     'Closed_RentalCreator'         => 'reset',
    // ];

    protected $rules = [
        // Rental
        'rental_date_collect' => 'required|date|before_or_equal:rental_date_return',
        'rental_date_return'  => 'required|date|after_or_equal:rental_date_collect',
        'rental_time_collect' => 'required|string|date_format:H:i:s', // eg. 07:30:00
        'rental_time_return'  => 'required|string|date_format:H:i:s',
        'rental_client_id'    => 'required|integer|min:0',
        'rental_vehicle_id'   => 'required|min:0',
        'rental_price_day'    => 'required|integer|min:0',
        'rental_notes'        => 'string|max:1000',
        // Client
        'client_name'         => 'required|string|max:80',
        'client_address'      => 'string|max:100',
        'client_phone_number' => 'string|max:50',
        'client_notes'        => 'string|max:1000',
        'client_rating'       => 'integer|min:-1|max:10',
    ];

    protected $messages = [
        // Rental
        'rental_date_collect.*' => 'Fecha de retiro es necesario, y antes/igual de la fecha de retorno.',
        'rental_date_return.*'  => 'Fecha retorno es necesario, y después/igual de la fecha de retiro.',
        'rental_time_return.*'  => 'Hora de retorno es necesario.',
        'rental_time_collect.*' => 'Hora de retiro es necesario.',
        'rental_client_id.*'    => 'Cliente es necesario.',
        'rental_vehicle_id.*'   => 'Vehiculo es necesario.',
        'rental_price_day.*'    => 'Precio es necesario. Solo dígitos.',
        'rental_notes.*'        => 'Notas 1000 caracteres máximo',
        // Client
        'client_name.*'         => 'Nombre es necesario. 80 caracteres máximo',
        'client_address.*'      => 'Dirección 100 caracteres máximo',
        'client_phone_number.*' => 'Numero de Teléfono 50 caracteres máximo',
        'client_notes.*'        => 'Notas 1000 caracteres máximo',
    ];

    public function mount(): void
    {
        $this->vehicle = new Vehicle();
        // no need to load all data for this until requested
    }

    protected function loadModelData(): void
    {
        // no need to load all data for this until requested
    }

    public function render(): View
    {
        if(userCan('edit rentals')) {
            return view('livewire.rental-creator');
        }
        return view('app.unauthorized');
    }

    public function handleEvent_CreateRentalWithVehicleDate(int $vehicle_id, string $mysqlDate): void
    {
        // $this->alertDebug("handleEvent_CreateRentalWithVehicleDate(int $vehicle_id, string $mysqlDate)");

        // Check params
        if(!DateLib::isMysqlDate($mysqlDate)) {
            throw new \Exception('Incorrect Date Format: '.$mysqlDate);
        }

        // Reset
        $this->reset();
        $this->resetDataModifiedSaved();

        // Relational data
        $this->vehicle = Vehicle::findOrFail($vehicle_id);

        // Rental
        $this->rental_vehicle_id = $vehicle_id;
        $this->rental_date_collect = $mysqlDate;
        $this->rental_date_return = $mysqlDate;
        $this->rental_price_day = $this->vehicle->vehicle_price;
        $this->rental_days_to_charge = 0;
        $this->rental_price_total = ($this->rental_days_to_charge * $this->vehicle->vehicle_price);
    }

    public function save(): void
    {
        $this->validateAndSaveAll();
    }

    protected function validateAndSaveAll(): void
    {
        // Validate everything
        $this->validate();

        // Client
        // client_id will change when they select another client,
        if(empty($this->rental_client_id)) {
            $client = new Client();
        }
        else {
            $client = Client::findOrFail($this->rental_client_id);
        }
        // Fill & Save
        // By default the model will only save if isDirty
        $client->fill([
                          'name'         => $this->client_name,
                          'address'      => $this->client_address,
                          'phone_number' => $this->client_phone_number,
                          'notes'        => $this->client_notes,
                          'rating'       => $this->client_rating,
                      ]
        );
        $client->save();
        // Set foreign key(s)
        $this->rental_client_id = $client->id;

        // Vehicle
        $this->rental_vehicle_id = $this->vehicle->id;

        // Rental
        $rental = new Rental();
        $rental->fill([
                          'date_collect'   => $this->rental_date_collect,
                          'time_collect'   => $this->rental_time_collect,
                          'date_return'    => $this->rental_date_return,
                          'time_return'    => $this->rental_time_return,
                          'client_id'      => $this->rental_client_id,
                          'vehicle_id'     => $this->rental_vehicle_id,
                          'price_day'      => $this->rental_price_day,
                          'days_to_charge' => $this->rental_days_to_charge,
                          'notes'          => $this->rental_notes,
                      ]);
        $rental->save();
        $this->rental_id = $rental->id;

        $this->setDataSaved();
        $this->alertSuccess('Alquiler guardado');
        $this->dispatch('CreatedRental', id: $rental->id);
    }

    public function handleEvent_SelectedClient(int $id): void
    {
        // $this->alertDebug('RentalCreator handleEvent_SelectedClient...');

        $this->setDataModified();

        if($id <= 0) {
            // Reset Client
            $this->rental_client_id = 0;
            $this->reset('client_name', 'client_address', 'client_phone_number', 'client_notes', 'client_rating');
        }
        else {
            // Load Client
            try {
                // Check
                $client = Client::findOrFail($id);
                // Set property
                $this->rental_client_id = $id;
                // Validate property
                $this->validateOnly('rental_client_id');
                // Apply changes to data
                $this->client_name = $client->name;
                $this->client_address = $client->address;
                $this->client_phone_number = $client->phone_number;
                $this->client_notes = $client->notes;
                $this->client_rating = $client->rating;
            }
            catch(\Throwable $ex) {
                $this->alertError('Error '.$id);
            }
        }
    }

    /**
     * !DEV NOTE: Required for event to be able to call it.
     *
     * @param ...$properties
     * @return void
     */
    function reset(...$properties): void
    {
        $this->resetDataModifiedSaved();
        parent::reset($properties);
    }

    /**
     * Date has been selected by the date picker.
     *
     * @param string $field The Livewire/Alpinejs entangled variable name used with the input element.
     * @param string $mysqlDateString
     * @return void
     */
    public function handleEvent_DateSelected(string $field, string $mysqlDateString): void
    {
        // $this->alertDebug("RentalCreator handleEvent_DateSelected ($field, $mysqlDateString)...");

        // Calculated
        if($field === 'rental_date_collect' || $field === 'rental_date_return') {
            $this->{$field} = $mysqlDateString;
            $this->setCalculatedValues();
        }
    }

    public function updated(string $propertyName): void
    {
        // $this->alertDebug("RentalCreator detect updated: $propertyName");
        $this->validateOnly($propertyName);
        $this->setCalculatedValues();
    }

    protected function setCalculatedValues(): void
    {
        $this->rental_days_to_charge = (DateLib::diffInDaysMysqlDates($this->rental_date_collect, $this->rental_date_return) ?: 1);
        $this->rental_price_total = $this->rental_days_to_charge * intval($this->rental_price_day ?: 0);
    }
}
