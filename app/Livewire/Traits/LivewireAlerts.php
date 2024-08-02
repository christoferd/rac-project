<?php

namespace App\Livewire\Traits;

trait LivewireAlerts
{
    /**
     * Emit 'addAlert' event
     */
    function addAlert(string $type, string $message)
    {
        $this->dispatch('AddAlert', $type, $message);
    }

    function alertSuccess(string $message)
    {
        $this->addAlert('success', $message);
    }

    function alertWarning(string $message)
    {
        $this->addAlert('warning', $message);
    }

    function alertError(string $message)
    {
        $this->addAlert('error', $message);
    }

    function alertInfo(string $message)
    {
        $this->addAlert('info', $message);
    }

    /**
     * Alert information if not environment production
     */
    function alertDebug(string $message)
    {
        if(!app()->environment('production')) {
            $this->addAlert('info', 'DEBUG: '.$message);
        }
    }

}
