<?php

namespace App\Livewire;

use App\Models\Client;

use App\Livewire\Traits\DataModifiedSaved;
use Illuminate\Contracts\View\View;

/**
 * Emits: UpdatedClient
 */
class ClientEditor extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved;

    protected string $objectName = 'Client';

    public int $client_id = 0;
    public string $name = '';
    public string $address = '';
    public string $phone_number = '';
    public string $notes = '';
    public int $rating = 1;
    public string $created_at = '';
    public string $updated_at = '';

    protected $listeners = [
        'DeleteClient'              => 'handleEvent_DeleteClient',
        'ClickedEditClient'         => 'handleEvent_ClickedEditClient',
        'ClientEditorSave'          => 'validateAndSave',
        'DeleteClient_ClientEditor' => 'handleEvent_DeleteClient',
        'DeletedClient'             => 'handleEvent_DeletedClient',
        // --
        'ClosedClientEditor'        => 'reset',
    ];

    // function getRules()
    // {
    //     return [
    //         'name'         => 'required|string|max:80',
    //         'address'      => 'string|max:100',
    //         'phone_number' => 'string|max:20',
    //         'notes'        => 'string|max:300',
    //         'rating'       => 'integer|min:-1|max:10',
    //     ];
    // }

    public function mount(int $clientId = 0): void
    {
        $this->loadModelData($clientId);
    }

    protected function loadModelData(int $clientId = 0): void
    {
        $this->resetDataModifiedSaved();
        // Model
        if($clientId) {
            $client = Client::findOrFail($clientId);
            $this->client_id = $clientId;
            $this->name = $client->name;
            $this->address = $client->address;
            $this->phone_number = $client->phone_number;
            $this->notes = $client->notes;
            $this->rating = $client->rating;

            // info for tooltip
            $this->created_at = $client->created_at;
            $this->updated_at = $client->updated_at;
        }
    }

    public function render(): View
    {
        if(userCan('edit clients')) {
            return view('livewire.client-editor');
        }
        return view('app.unauthorized');
    }

    public function handleEvent_ClickedEditClient(int $id): void
    {
        $this->loadModelData($id);
    }

    /**
     * Called automatically by Livewire
     * Runs after any update to the Livewire component's data (Using wire:model, not directly inside PHP)
     * https://laravel-livewire.com/docs/2.x/lifecycle-hooks
     */
    public function updated(): void
    {
        $this->validateAndSave();
    }

    public function validateAndSave(): void
    {
        // Status
        $this->setDataModified();

        // Validate & Save & dispatch
        $client = Client::findOrFail($this->client_id);
        $this->submitEditFormObjective($client);

        // Status
        $this->setDataSaved();
    }

    public function handleEvent_DeleteClient(int $id): void
    {
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        try {
            Client::findOrFail($id)->delete();
        }
        catch(\Throwable $ex) {
            $this->handleException($ex, 'Unable to delete the record.');
            return;
        }
        $this->alertSuccess(__('Record Deleted'));
        $this->dispatch('DeletedClient');
    }

    function handleEvent_DeletedClient(int $id): void
    {
        $this->reset();
    }

}
