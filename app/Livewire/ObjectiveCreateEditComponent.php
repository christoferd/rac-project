<?php

namespace App\Livewire;

use App\Library\ArrayLib;
use App\Library\SessionAlert;
use App\Models\ObjectiveModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Nette\Schema\ValidationException;
use Str;
use Throwable;
use App\Livewire\Traits\LivewireModals;

class ObjectiveCreateEditComponent extends ObjectiveComponent
{
    use LivewireModals;

    /**
     * Used when redirecting, only when not in Modal
     * Name of route to edit.
     *
     * @var string Example: clients.edit
     */
    protected string $routeToEdit = '';

    /**
     * This can contain spaces. Example: "System Config", "Text Message"
     *
     * @var string
     */
    protected string $objectName = 'ObjectiveComponent-objectName';

    // Validation
    protected $rules;
    protected $messages;

    /**
     * Validate, fill model, save model.
     * ! Note: this will emit Livewire Alerts
     *
     * @param ObjectiveModel &$model !Warning: this method directly affects the model
     * @param string          $field
     * @param string|int      $value
     * @return bool True on success. Else false returned.
     * @throws Throwable|ValidationException
     */
    public function validateAndUpdateSingleField(ObjectiveModel &$model, string $field)
    {
        // Validation
        $this->rules = ($model->getRulesEdit() ?? $model->getRulesCreate());
        $this->messages = $model->getMessages();
        $value = $this->$field;

        $this->resetValidation();
        $this->validateOnly($field, $this->rules, $this->messages, [$field => $value]);

        // Save Record
        try {
            $model->fill([$field => $value]);
            $res = $model->save();
            if(!$res) {
                // Error
                $this->alertError(__('Error, not able to update record'));
            }
            else {
                // Success
                $this->showModal(false);
                $this->dispatch('Updated'.Str::studly($this->objectName), id: $model->getKey());
                // $this->alertDebug(__FUNCTION__.'| dispatch event: Updated'.Str::studly($this->objectName).', '.$model->getKey());
                return true;
            }
        }
        catch(Throwable $ex) {
            $this->alertError(__('Error, not able to update record').'. '.$ex->getMessage());
            $this->alertDebug($ex->getMessage());
            \Log::error($ex);
        }
        return false;
    }

    /**
     * Validate, fill model, save model.
     * ! Note: this will emit Livewire Alerts
     *
     * @param ObjectiveModel &$model !Warning: this method directly affects the model
     * @return bool|RedirectResponse True on success. Else false returned.
     * @throws Throwable
     */
    public function submitCreateFormObjective(ObjectiveModel &$model)
    {
        // Validation
        $this->rules = $model->getRulesCreate();
        $this->messages = $model->getMessages();
        try {
            $validData = $this->validate();
        }
        catch(\Throwable $ex) {
            $this->alertError(__('Data Process Error: See messages in the form.'));
            throw $ex;
        }

        // Check at least one field has altered data from the default values
        if(!ArrayLib::hasDifferences($model->getDefaultValues(), $validData, true)) {
            $this->alertError(__('Enter some information'));
            return false;
        }

        // Save Record
        try {
            $model->fill($validData);
            $res = $model->save();
            if(!$res) {
                // Error
                $this->alertError(__('Error, not able to create record'));
            }
            else {
                // Success
                $this->dispatch('Created'.Str::studly($this->objectName), id: $model->getKey());
                // $this->alertDebug('dispatch event: '.'Created'.Str::studly($this->objectName).', '.$model->getKey());
                if($this->isModal) {
                    // Modal
                    $this->closeModal();
                    $this->alertSuccess(__('Record Created'));
                    return true;
                }
                else {
                    // Not modal
                    SessionAlert::success(__('Record Created'));
                    // Optional redirect
                    if(!empty($this->routeToEdit)) {
                        return Redirect::route($this->routeToEdit, $model->getKey());
                    }
                }
            }
        }
        catch(Throwable $ex) {
            $this->alertError(__('Error, not able to create record').'. '.$ex->getMessage());
            $this->alertDebug($ex->getMessage());
            \Log::error($ex);
        }
        return false;
    }

    public function handleEvent_AutocompleteComponent_setValue(string $inputId, string $value)
    {
        if(!\property_exists($this, $inputId)) {
            throw new \Exception('Property ('.$inputId.') does not exist in class: '.__CLASS__);
        }
        $this->$inputId = $value;
    }

    /**
     * Validate, fill model, save model.
     * ! Note: this will emit Livewire Alerts
     *
     * @param ObjectiveModel &$model !Warning: this method directly affects the model
     * @return bool True on success. Else false returned.
     * @throws Throwable
     */
    public function submitEditFormObjective(ObjectiveModel &$model)
    {
        // Validation
        $this->rules = ($model->getRulesEdit() ?? $model->getRulesCreate());
        $this->messages = $model->getMessages();
        try {
            $validData = $this->validate();
        }
        catch(\Throwable $ex) {
            $this->alertError(__('Data Process Error: See messages in the form.'));
            $this->alertDebug($ex->getMessage());
            throw $ex;
        }

        // Save Record
        try {
            $model->fill($validData);
            $res = $model->save();
            if(!$res) {
                // Error
                $this->alertError(__('Error, not able to update record'));
            }
            else {
                // Success
                $this->showModal(false);
                $this->dispatch('Updated'.Str::studly($this->objectName), id: $model->getKey());
                // $this->alertDebug(__FUNCTION__.'| dispatch event: Updated'.Str::studly($this->objectName).', '.$model->getKey());
                return true;
            }
        }
        catch(Throwable $ex) {
            $this->alertError(__('Error, not able to update record').'. '.$ex->getMessage());
            $this->alertDebug($ex->getMessage());
            \Log::error($ex);
        }
        return false;
    }

}
