<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Livewire\Traits\LivewireAlerts;

class ObjectiveComponent extends Component
{
    use LivewireAlerts;

    /**
     * Can be used to deal with class models in an abstract method, and not need to have created and stored until required.
     * For example - see ordering methods.
     *
     * @var string
     */
    public string $modelClassName = '';

    /**
     * This can be used in the component view to check if we want to display debug info in the view.
     *
     * @var bool
     */
    public bool $componentDebug = false;

    /**
     * Is this component in a modal?
     *
     * @var bool
     */
    public bool $modal = false;

    /**
     * Initialise with modal open?
     *
     * @var bool
     */
    public bool $showModal = false;

    function handleException(\Throwable $ex, string $message = '')
    {
        // Localized message to user
        $this->alertError(__($message));
        // Message to developer
        $this->alertDebug($ex->getMessage());
        // Log error message & Exception
        Log::error(\get_called_class().'| '.$message);
        Log::error($ex);
    }

}
