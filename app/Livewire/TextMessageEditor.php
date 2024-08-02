<?php

namespace App\Livewire;

use App\Livewire\Traits\DataModifiedSaved;
use App\Models\TextMessage;
use App\Livewire\Traits\LivewireAlerts;

class TextMessageEditor extends ObjectiveCreateEditComponent
{
    use DataModifiedSaved, LivewireAlerts;

    protected string $objectName = 'Message';

    // Model Data
    public int $model_id = 0;
    public string $message_title = '';
    public string $message_notes = '';
    public string $message_content = '';

    protected $listeners = [
        'ClickedCreateTextMessage' => 'handleEvent_ClickedCreateTextMessage',
        'ClickedEditTextMessage'   => 'handleEvent_ClickedEditTextMessage',

        'DeleteTextMessage_TextMessageEditor' => 'handleEvent_DeleteTextMessage',
        'DeletedTextMessage'                  => 'reset',

        // --
        'Closed_EditTextMessage'              => 'reset',
    ];

    public function render()
    {
        if(userCan('access messages')) {
            return view('livewire.text-message-editor');
        }
        return view('app.unauthorized');
    }

    protected function loadModelData(int $model_id = 0): void
    {
        $this->resetDataModifiedSaved();
        $this->reset();

        if($model_id > 0) {
            $model = TextMessage::findOrFail($model_id);
            $this->model_id = $model_id;
            $this->message_title = $model->message_title;
            $this->message_notes = $model->message_notes;
            $this->message_content = $model->message_content;
        }
    }

    public function handleEvent_ClickedCreateTextMessage(): void
    {
        $model = new TextMessage();
        $model->save();
        $this->loadModelData($model->id);
        $this->dispatch('CreatedTextMessage', $model->id);
    }

    public function handleEvent_ClickedEditTextMessage(int $id): void
    {
        // $this->alertDebug(__CLASS__." handleEvent_ClickedEditTextMessage(int $id)");
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
        $model = TextMessage::findOrFail($this->model_id);
        $this->submitEditFormObjective($model);

        // Status
        $this->setDataSaved();
    }

    public function handleEvent_DeleteTextMessage(int $id): void
    {
        // $this->alertDebug("function handleEvent_DeleteTextMessage(int $id )");

        if(!userCan('delete records')) {
            $this->alertError(__('Unauthorized'));
            return;
        }

        try {
            $model = TextMessage::findOrFail($id);
            if(!$model->allowDelete()) {
                $this->alertError(__('Unable to delete the record.').' '.$model->message);
                return;
            }
            $model->delete();
            $this->reset();
            $this->alertSuccess(__('Record Deleted'));
            $this->dispatch('DeletedMessage');
        }
        catch(\Throwable $ex) {
            $this->handleException($ex, 'Unable to delete the record.');
        }
    }



}
