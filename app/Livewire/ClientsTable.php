<?php

namespace App\Livewire;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Livewire\WithPagination;

class ClientsTable extends ObjectiveComponent
{
    use WithPagination;

    public bool $showSearchBox = true;
    public bool $showButtonNew = true;
    public bool $clickRowAction = true;
    public string $searchString = '';
    public int $paginationPerPage = 10;

    /**
     * @var string The form checkbox "name" to be used with all the checkboxes. E.g. "clients"
     */
    public string $checkboxes = '';

    protected $listeners = [
        'CreatedClient'             => '$refresh',
        'UpdatedClient'             => '$refresh',
        'DeletedClient'             => '$refresh',
        'DeleteClient_ClientsTable' => 'handleEvent_DeleteClient_ClientsTable',
    ];

    public function mount(string $checkboxes = '', bool $clickRowAction = true): void
    {
        $this->checkboxes = $checkboxes;
        $this->clickRowAction = $clickRowAction;
    }

    public function render(): View
    {
        if(trim($this->searchString) !== '') {
            $clients = Client::search($this->searchString)
                             ->orderBy('id', 'desc')
                             ->paginate($this->paginationPerPage);
        }
        else {
            $clients = Client::orderBy('id', 'desc')
                             ->paginate($this->paginationPerPage);
        }

        if(userCan('access clients')) {
            return view('livewire.clients-table', ['clients' => $clients]);
        }
        return view('app.unauthorized');
    }

    public function handleEvent_DeleteClient_ClientsTable(int $id): void
    {
        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        // Delete Client
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

    public function updatedSearchString(string $value): void
    {
        $this->resetPage();
    }

}
