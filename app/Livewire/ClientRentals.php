<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Rental;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ClientRentals extends Component
{
    public int $clientId;
    public $client = null;
    public $rentals = null;

    protected $listeners = [
        'ClickedClientRentals' => 'loadModelData',
        // --
        'ClosedClientRentals'  => 'reset',
    ];

    public function mount(int $clientId = 0): void
    {
        $this->clientId = $clientId;
        $this->loadModelData($clientId);
    }

    public function loadModelData(int $client_id = 0): void
    {
        if(empty($client_id)) {
            return;
        }
        $this->client = Client::find($client_id);
        $this->rentals = Rental::with('vehicle')->where('client_id', $client_id)
            // ! No pagination supported
                               ->get();
    }

    public function render(): View
    {
        return view('livewire.client-rentals');
    }
}
