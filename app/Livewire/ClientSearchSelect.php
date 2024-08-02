<?php

namespace App\Livewire;

use App\Models\Client;

/**
 * Emits Livewire Events:
 *      'SelectedClient', [$clientId]: when they click a search result.
 */
class ClientSearchSelect extends ObjectiveComponent
{
    public $search = '';
    public $searchResults = [];
    public $searchResultsLimit = 10;
    public $minCharsToSearch = 2;
    public $showIconLeft = true;
    public $resultsPanelClass = '';

    protected $searchFields = [
        'name', 'address', 'phone_number', 'notes'
    ];
    protected $searchSelectFields = [
        'id', 'name', 'address', 'phone_number', 'notes', 'rating'
    ];

    public function mount(bool $showIconLeft = true, string $resultsPanelClass = '')
    {
        $this->showIconLeft = $showIconLeft;
        $this->resultsPanelClass = $resultsPanelClass;
    }

    public function render()
    {
        return view('livewire.client-search-select');
    }

    public function updatedSearch()
    {
        if(strlen($this->search) < $this->minCharsToSearch) {
            $this->searchResults = [];
            return;
        }

        $model = new Client();
        $query = $model->searchItems(Client::query(), $this->searchFields, $this->search);
        $response = $query->select($this->searchSelectFields)
                          ->limit($this->searchResultsLimit)
                          ->get();

        $this->searchResults = $response->toArray();
    }

    public function select(int $clientId)
    {
        $this->resetComponent();
        $this->dispatch('SelectedClient', $clientId);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->updatedSearch();
    }

    public function resetComponent()
    {
        $this->reset([
                         'search',
                         'searchResults',
                         'searchResultsLimit',
                         'minCharsToSearch',
                     ]);
    }

}
