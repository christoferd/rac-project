<?php

namespace App\Livewire;

use App\Models\Client;
use Closure;
use Illuminate\Contracts\View\View;

class ClientDetails extends ObjectiveComponent
{
    public int $clientId = 0;
    protected array $clientArray = [];
    public int $allowRemove = 0;
    public int $allowEdit = 0;
    public int $allowSearch = 0;
    public int $allowViewRentals = 0;

    protected $listeners = [
        // by ClientEditor
        'UpdatedClient' => 'handleEvent_UpdatedClient'
    ];

    public function mount(string $clientId, string $allowRemove = '0', string $allowEdit = '0', string $allowSearch = '0',
                          string $allowViewRentals = '0')
    {
        $this->clientId = intval($clientId);
    }

    public function render(): View|Closure|string
    {
        $clientId = $this->clientId;
        $this->loadData();
        $clientArray = $this->clientArray;
        return view('livewire.client-details', \compact('clientArray', 'clientId'));
    }

    function loadData()
    {
        if($this->clientId) {
            $record = Client::findOrFail($this->clientId);
        }
        else {
            $record = new Client();
        }
        $this->clientArray = $record->toArray();
    }

    public function handleEvent_SetClientDetailsId(int $id)
    {
        $this->clientId = $id;
    }

    public function handleEvent_UpdatedClient(int $id): void
    {
        // $this->alertDebug(__CLASS__." &gt; handleEvent_UpdatedClient(int $id)");
        $this->clientId = $id;
        $this->loadData();
    }
}
