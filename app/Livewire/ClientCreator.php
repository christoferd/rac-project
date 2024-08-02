<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Models\Client;
use Illuminate\Contracts\View\View;

class ClientCreator extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved;

    protected string $objectName = 'Client';

    public string $name = '';
    public string $address = '';
    public string $phone_number = '';
    public string $notes = '';
    public int $rating = 1;

    protected $listeners = [
        'ClickedCreateClient' => 'handleEvent_ClickedCreateClient',
        // --
        'Closed_CreateClient' => 'reset',
    ];

    function getRulesCreate()
    {
        return [
            'name'         => 'required|string|max:80',
            'address'      => 'string|max:100',
            'phone_number' => 'string|max:20',
            'notes'        => 'string|max:300',
            'rating'       => 'integer|min:-1|max:10',
        ];
    }

    public function handleEvent_ClickedCreateClient(): void
    {
        $this->resetDataModifiedSaved();
        $this->reset();
    }

    public function render(): View
    {
        return view('livewire.client-creator');
    }

    public function save(): void
    {
        // Status
        $this->setDataModified();

        // Save
        $client = new Client();
        $this->submitCreateFormObjective($client);

        // Status
        $this->setDataSaved();
    }

}
