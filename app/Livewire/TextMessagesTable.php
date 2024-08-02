<?php

namespace App\Livewire;

use App\Models\TextMessage;
use Illuminate\Support\Facades\Log;

class TextMessagesTable extends ObjectiveComponent
{
    public array $textMessages = [];

    protected $listeners = [
        // Events by ObjectiveCreateEditComponent
        'CreatedMessage' => 'loadData',
        'UpdatedMessage' => 'loadData',
        // Event by TextMessageEditor
        'DeletedMessage' => 'loadData',
    ];

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Active first
        $this->textMessages = TextMessage::orderBy('message_title', 'asc')
                                         ->get()
                                         ->toArray();
    }

    public function render()
    {
        if(userCan('access messages')) {
            return view('livewire.text-messages-table');
        }
        return view('app.unauthorized');
    }

}
