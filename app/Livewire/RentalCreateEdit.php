<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Library\DateLib;
use App\Models\Client;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

// use App\Traits\HasClientImagesTrait;

class RentalCreateEdit extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved;

    // use HasClientImagesTrait;

    protected string $objectName = 'Rental';

    protected Rental|null $rental = null;

    // Data
    public int $rental_id = 0;
    // Rental Data
    public int $client_id = 0;
    public int $vehicle_id = 0;
    public string $date_collect = '';
    public string $time_collect = '';
    public string $date_return = '';
    public string $time_return = '';
    public int $price_day = 0;
    public int $days_to_charge = 0;
    public int $price_total = 0;
    public string $notes = '';

    // Relational Data
    public array $clientDetails = [];

    /**
     * @var Vehicle
     */
    // public $vehicle = null;

    protected $listeners = [
        // by RentalCalendar
        'CreateRentalWithVehicleDate'      => 'handleEvent_CreateRentalWithVehicleDate',
        // Notified by another component on calendar
        'ClickedEditRental'                => 'handleEvent_ClickedEditRental',
        //  by rental-create-edit, and confirmed in modal
        'DeleteRental'                     => 'handleEvent_DeleteRental',
        // selected a Client from dropdown list in the form
        'SelectedClient_RentalCreateEdit'  => 'handleEvent_SelectedClient',
        // by ClientSearchSelect::select() PHP
        'SelectedClient'                   => 'handleEvent_SelectedClient',
        // by ClientCreator
        'CreatedClient'                    => 'handleEvent_CreatedClient',
        // by ClientEditor
        'UpdatedClient'                    => 'handleEvent_UpdatedClient',
        // by client-action-buttons
        'RemoveClient'                     => 'handleEvent_RemoveClient',
        // Notified by another component
        'DateSelected_RentalCreateEdit'    => 'handleEvent_DateSelected',
        // by vehicle-select component
        'SelectedVehicle_RentalCreateEdit' => 'handleEvent_SelectedVehicle',
        // --
        'Closed_RentalCreateEdit'          => 'reset',
    ];

    public function mount(): void
    {
        // no need to load all data for this until requested
    }

    protected function loadModel(): void
    {
        $this->rental = Rental::findOrFail($this->rental_id);
    }

    protected function loadModelData(int $rentalId): void
    {
        // Setup with defaults first before loading
        $this->reset();

        if($rentalId) {
            // Model
            $this->rental_id = $rentalId;
            $this->loadModel();
            $rental =& $this->rental;
            $this->client_id = $rental->client_id;
            $this->vehicle_id = $rental->vehicle_id;
            $this->date_collect = $rental->date_collect;
            $this->time_collect = $rental->time_collect;
            $this->date_return = $rental->date_return;
            $this->time_return = $rental->time_return;
            $this->price_day = $rental->price_day;
            $this->days_to_charge = $rental->days_to_charge;
            $this->price_total = $rental->price_total;
            $this->notes = $rental->notes;

            // Calculated values
            $this->setCalculatedValues();

            // Relational
            $this->loadDataClient();
        }
    }

    public function render(): View
    {
        if(userCan('edit rentals')) {
            return view('livewire.rental-create-edit');
        }
        return view('app.unauthorized');
    }

    public function loadDataClient()
    {
        if($this->client_id) {
            $this->clientDetails = Client::withTrashed()
                                         ->find($this->client_id)
                                         ->toArray();
        }
        else {
            $this->clientDetails = [];
        }
    }

    public function handleEvent_CreateRentalWithVehicleDate(int $vehicle_id, string $mysqlDate): void
    {
        // $this->alertDebug("handleEvent_CreateRentalWithVehicleDate(int $vehicle_id, string $mysqlDate)");

        // Check params
        // @todo Add Library/Package (use spatie package template) to check params, throw exception otherwise
        if(!DateLib::isMysqlDate($mysqlDate)) {
            throw new \Exception('Incorrect Date Format: '.$mysqlDate);
        }

        // Reset
        $this->reset();
        // $this->resetDataModifiedSaved();

        // Rental
        $this->vehicle_id = $vehicle_id;
        // @todo Create DbHelper getCellValue( table, id, field )
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $this->price_day = intval($vehicle->vehicle_price);

        $this->date_collect = $mysqlDate;
        $this->date_return = $mysqlDate;
        $this->days_to_charge = 0;
        $this->price_total = 0;
    }

    /*
     * Called by Livewire
     */
    public function save(): void
    {
        $this->setDataModified();

        if($this->rental_id) {
            // Edit mode
            $this->alertError('Code error: save() should only be used with Create mode, $this--rental_id has a value: '.$this->rental_id);
        }
        else {
            // Create mode
            $rental = new Rental();
            $this->submitCreateFormObjective($rental);
            $this->setDataSaved();
            // Reload the model in case there's other stuff tied to it, like calculated values
            $this->loadModelData($rental->id);
            // This is already dispatched by the ObjectiveCreateEditComponent !!!
            // $this->dispatch('CreatedRental', id: $rental->id);
        }
    }

    public function handleEvent_SelectedClient(int $id): void
    {
        // $this->alertDebug(__CLASS__.' handleEvent_SelectedClient '.$id);
        $this->selectClient($id);
    }

    public function handleEvent_CreatedClient(int $id): void
    {
        // $this->alertDebug(__CLASS__.' handleEvent_CreatedClient '.$id);
        $this->selectClient($id);
    }

    public function selectClient(int $id)
    {
        if(!empty($id) && $id === $this->client_id) {
            return;
        }

        $this->client_id = $id;
        $this->loadDataClient();
        $this->updated('client_id');
    }

    /**
     * !DEV NOTE: Required for event to be able to call it.
     *
     * @param ...$properties
     * @return void
     */
    function reset(...$properties): void
    {
        $this->resetValidation();
        $this->resetDataModifiedSaved();
        parent::reset($properties);
    }

    /**
     * Date has been selected by the date picker.
     *
     * @param string $field
     * @param string $mysqlDateString
     * @return void
     */
    public function handleEvent_DateSelected(string $field, string $mysqlDateString): void
    {
        // $this->alertDebug("RentalCreateEdit handleEvent_DateSelected ($field, $mysqlDateString)...");

        // double-check date, just in case
        if(!DateLib::isMysqlDate($mysqlDateString)) {
            $this->alertError('Error, date format: '.$mysqlDateString);
            return;
        }

        $this->$field = $mysqlDateString;
        $this->updated($field);
    }

    public function updated(string $propertyName): void
    {
        // $this->alertDebug(__CLASS__.": updated: $propertyName = ".$this->$propertyName);
        // $this->alertDebug(__CLASS__.': this->rental_id = '.$this->rental_id);

        $this->setDataModified();

        // Only save live in Edit Mode
        if($this->rental_id) {
            $this->loadModel();
            $this->rules = $this->rental->getRulesEdit();
            if(!isset($this->rules[$propertyName])) {
                // $this->alertDebug('skip this property '.$propertyName);
                return;
            }

            $this->validateAndUpdateSingleField($this->rental, $propertyName);
            $this->setDataSaved();
        }

        // Calculated values updated when data changes
        $this->setCalculatedValues();
    }

    protected function setCalculatedValues(): void
    {
        $this->days_to_charge = (DateLib::diffInDaysMysqlDates($this->date_collect, $this->date_return) ?: 1);
        $this->price_total = $this->days_to_charge * intval($this->price_day ?: 0);
    }

    public function handleEvent_ClickedEditRental(int $id): void
    {
        $this->loadModelData($id);
    }

    public function handleEvent_RemoveClient()
    {
        // $this->alertDebug('handleEvent_RemoveClient');

        $this->removeClient();
    }

    public function handleEvent_DeleteRental(int $id): void
    {
        //$this->alertDebug("handleEvent_DeleteRental(int $id)");
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        try {
            // Delete
            Rental::findOrFail($id)->delete();
        }
        catch(\Throwable $ex) {
            $this->handleException($ex, 'Unable to delete the record.');
            return;
        }
        $this->alertSuccess(__('Record Deleted'));
        $this->dispatch('DeletedRental', $id);

        $this->reset();
    }

    function removeVehicle()
    {
        $this->vehicle_id = 0;
        // Do not save because Vehicle is Required
    }

    function removeClient()
    {
        $this->selectClient(0);
    }

    function handleEvent_SelectedVehicle(int $id)
    {
        $this->vehicle_id = $id;
        $this->updated('vehicle_id');
    }

    function handleEvent_UpdatedClient(int $id)
    {
        // $this->alertDebug(__CLASS__."  handleEvent_UpdatedClient(int $id)");

        if($id === $this->client_id) {
            $this->loadDataClient();
        }
    }

}
