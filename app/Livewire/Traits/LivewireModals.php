<?php

namespace App\Livewire\Traits;

trait LivewireModals
{
    /**
     * Is this component in a modal?
     *
     * @var bool
     */
    public bool $isModal = false;

    /**
     * Set to true to cause showing Modal/OffCanvass
     *
     * @var bool
     */
    public bool $showModal = false;

    public function showModal(bool $bool = true): void
    {
        $this->showModal = $bool;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }
}
