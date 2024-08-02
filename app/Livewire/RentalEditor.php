<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Library\DateLib;
use App\Models\Client;
use App\Models\ClientImage;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Contracts\View\View;
use Livewire\WithFileUploads;

class RentalEditor extends ObjectiveComponent
{
    /*
     * Not Used // Chris D.
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     *
     *
     *  See: RentalCreateEdit
     *
     *
     *
     *
     */

    use DataModifiedSaved, WithFileUploads;

    public Rental|null $rental = null;
    public Client|null $client = null;
    public Vehicle|null $vehicle = null;
    // public array $clientImages = [];
    // Special vars required by view
    // public $rental_date_collect = '';
    // public $rental_date_return = '';

    // protected $listeners = [
    //     // selected a Client from dropdown list in the form
    //     'SelectedClient_RentalEditor'  => 'handleEvent_SelectedClient',
    //     'RemovedClient_RentalEditor'   => 'handleEvent_RemovedClient',
    //     // selected a Vehicle from dropdown list in the form
    //     'SelectedVehicle_RentalEditor' => 'handleEvent_SelectedVehicle',
    //     // selected a Date from date picker in the form
    //     'DateSelected_RentalEditor'    => 'handleEvent_DateSelected',
    //     // clicked link to delete, and/or clicked button in confirmation
    //     'DeleteRental'    => 'handleEvent_DeleteRental',
    //     // Notified by another component
    //     'ClickedEditRental'            => 'handleEvent_ClickedEditRental',
    //     // by pressing hotkey
    //     'RentalEditorSave'             => 'validateAndSave',
    //     // --
    //     'Closed_RentalEditor'          => 'reset',
    //     // 'refreshComponent'    => '$refresh',
    // ];

    protected $rules = [
        // Rental
        'rental.date_collect'   => 'required|date|before_or_equal:rental.date_return',
        'rental.date_return'    => 'required|date|after_or_equal:rental.date_collect',
        'rental.time_collect'   => 'required|string|date_format:H:i:s', // eg. 07:30:00
        'rental.time_return'    => 'required|string|date_format:H:i:s',
        'rental.client_id'      => 'required|integer|min:0',
        'rental.vehicle_id'     => 'required|min:0',
        'rental.price_day'      => 'required|integer|min:0',
        'rental.days_to_charge' => 'required|integer|min:0',
        'rental.notes'          => 'string|max:1000',
        // Client
        'client.name'           => 'required|string|max:80',
        'client.address'        => 'string|max:100',
        'client.phone_number'   => 'string|max:50',
        'client.notes'          => 'string|max:1000',
        'client.rating'         => 'integer|min:-1|max:10',
    ];

    protected $messages = [
        // Rental
        'rental.date_collect.*'   => 'Fecha de retiro es necesario, y antes/igual de la fecha de retorno.',
        'rental.date_return.*'    => 'Fecha retorno es necesario, y después/igual de la fecha de retiro.',
        'rental.time_collect.min' => 'Hora de retiro es necesario.',
        'rental.time_return.min'  => 'Hora de retorno es necesario.',
        'rental.time_collect.max' => 'Hora de retiro es necesario.',
        'rental.time_return.max'  => 'Hora de retorno es necesario.',
        // Client
        'client_name.*'           => 'Nombre es necesario. 80 caracteres máximo',
        'client_address.*'        => 'Dirección 100 caracteres máximo',
        'client_phone_number.*'   => 'Numero de Teléfono 50 caracteres máximo',
        'client_notes.*'          => 'Notas 1000 caracteres máximo',
    ];

    public function mount(int $rentalId = 0): void
    {
        $this->loadModelData($rentalId);
    }

    public function render(): View
    {
        if(userCan('edit rentals')) {
            return view('livewire.rental-editor');
        }
        return view('app.unauthorized');
    }

    protected function loadModelData(int $rental_id = 0): void
    {
        $this->resetDataModifiedSaved();
        if($rental_id) {
            // Model
            $this->rental = Rental::findOrFail($rental_id);
            $this->loadVehicle($this->rental->vehicle_id ?: 0);
            $this->loadClient($this->rental->client_id ?: 0);

            // Setup Dates
            // - rental.date_collect
            if(empty($this->rental->date_collect)) {
                $this->rental->date_collect = date('Y-m-d');
            }
            // - rental.date_return
            if(empty($this->rental->date_return)) {
                $this->rental->date_return = date('Y-m-d');
            }
            // Special vars required by view
            // $this->setSharedViewData();
        }
        else {
            $this->reset();
        }
    }

    protected function loadVehicle(int $id): void
    {
        if($id) {
            $this->vehicle = Vehicle::findOrFail($id);
            $this->rental->vehicle_id = $id;
        }
        else {
            $this->vehicle = new Vehicle();
            $this->rental->vehicle_id = 0;
        }
    }

    protected function loadClient(int $id): void
    {
        if($id) {
            $this->client = Client::findOrFail($id);
            $this->rental->client_id = $id;
        }
        else {
            $this->client = new Client();
            $this->rental->client_id = 0;
        }
    }

    // /**
    //  * Special vars required by view
    //  *
    //  * @return void
    //  */
    // function setSharedViewData(): void
    // {
    //     $this->rental_date_collect = $this->rental->date_collect;
    //     $this->rental_date_return = $this->rental->date_return;
    // }

    public function handleEvent_ClickedEditRental(int $rental_id): void
    {
        $this->loadModelData($rental_id);
    }

    // Handle update of any field
    public function updated($propertyName): void
    {
        // $this->alertDebug('RentalEditor Detected Updated: '.$propertyName);

        // Validate any single field on update
        // https://laravel-livewire.com/docs/2.x/input-validation#real-time-validation
        $this->validateOnly($propertyName);

        // if($this->client->isDirty() || $this->rental->isDirty()) {
        $this->validateAndSave();
        // $this->loadModelData($this->rental->id);
        // }
    }

    protected function validateAndSave(): void
    {
        if(!$this->client->isDirty() && !$this->rental->isDirty()) {
            return;
        }

        // Status
        $this->setDataModified();
        // Validate
        $this->validate();
        // By default the model will only save if isDirty
        $this->client->save();
        $this->rental->save();
        // Special vars required by view
        // $this->setSharedViewData();
        // Status
        $this->setDataSaved();
        // Dispatch
        $this->dispatch('UpdatedRental', id: $this->rental->id);
    }

    public function handleEvent_SelectedClient(int $id): void
    {
        // $this->alertDebug('RentalEditor handleEvent_SelectedClient...');

        $this->setDataModified();

        try {
            $this->loadClient($id);
            $this->rental->client_id = $id;
            $this->validateOnly('rental.client_id');
            $this->rental->save();
            $this->client = Client::findOrFail($this->rental->client_id);
            // Special vars required by view
            // $this->setSharedViewData();
            $this->dispatch('UpdatedRental', id: $this->rental->id);
            $this->setDataSaved();
        }
        catch(\Throwable $ex) {
            $this->alertError('Error '.$id);
        }
    }

    public function handleEvent_SelectedVehicle(int $id): void
    {
        // $this->alertDebug('RentalEditor handleEvent_SelectedVehicle...');

        $this->setDataModified();

        try {
            $this->loadVehicle($id);
            $this->rental->vehicle_id = $id;
            $this->validateOnly('rental.vehicle_id');
            $this->rental->save();
            $this->vehicle = Vehicle::findOrFail($this->rental->vehicle_id);
            // Special vars required by view
            // $this->setSharedViewData();
            $this->dispatch('UpdatedRental', id: $this->rental->id);
            $this->setDataSaved();
        }
        catch(\Throwable $ex) {
            $this->alertError('Error '.$id);
        }
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
        // $this->alertDebug('RentalEditor handleEvent_DateSelected...');

        if($field === 'rental_date_collect') {
            $this->setDataModified();
            if(DateLib::isMysqlDate($mysqlDateString)) {
                $this->rental->date_collect = $mysqlDateString;
                $this->validate();
                $this->rental->save();
                // Special vars required by view
                // $this->setSharedViewData();
                $this->setDataSaved();
                $this->dispatch('UpdatedRental', id: $this->rental->id);
            }
            else {
                $this->alertError('Error, date format: '.$mysqlDateString);
            }
        }
        elseif($field === 'rental_date_return') {
            $this->setDataModified();
            if(DateLib::isMysqlDate($mysqlDateString)) {
                $this->rental->date_return = $mysqlDateString;
                $this->validate();
                $this->rental->save();
                // Special vars required by view
                // $this->setSharedViewData();
                $this->setDataSaved();
                $this->dispatch('UpdatedRental', id: $this->rental->id);
            }
            else {
                $this->alertError('Error, date format: '.$mysqlDateString);
            }
        }
        else {
            $this->alertError(e('handleEvent_DateSelected Error, unsupported entangle: '.$field));
        }
    }

    public function handleEvent_DeleteRental(int $id): void
    {
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        if(!empty($id) && $this->rental->id === $id) {
            $this->rental->delete();
            $this->loadModelData(0);
            $this->alertSuccess(__t('Rental', 'Deleted'));
            $this->dispatch('DeletedRental', $id);
        }
        else {
            $this->alertError('Error');
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

    public function handleEvent_RemovedClient(): void
    {
        $this->setDataModified();
        $this->client = new Client();
        $this->rental->client_id = 0;
    }
}
