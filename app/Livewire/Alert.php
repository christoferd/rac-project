<?php

namespace App\Livewire;

use Livewire\Component;

class Alert extends Component
{
    public array $alerts;

    protected $listeners = [
        'AddAlert'   => 'handleEvent_AddAlert',
        'AddAlertTranslate'   => 'handleEvent_AddAlertTranslate',
        'DemoAlerts' => 'demoAlerts',
    ];

    public function mount()
    {
        $this->alerts = [];
    }

    public function render()
    {
        return view('livewire.alert');
    }

    /**
     * Event listener function.
     * See: $listeners
     *
     * @param string $type
     * @param string $message
     */
    public function handleEvent_AddAlert(string $type, string $message)
    {
        $this->addAlert($type, $message);
    }

    /**
     * Event listener function.
     * See: $listeners
     *
     * @param string $type
     * @param string $message
     */
    public function handleEvent_AddAlertTranslate(string $type, string $message)
    {
        $this->addAlert($type, __($message));
    }

    protected function addAlert(string $type, string $message)
    {
        $this->alerts[] = ['type' => $type, 'message' => $message];
    }

    public function demoAlerts()
    {
        $this->addAlert('success', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit,'.
                                   ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $this->addAlert('error', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit,'.
                                 ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $this->addAlert('warning', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit,'.
                                   ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
        $this->addAlert('info', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit,'.
                                ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
    }
}
