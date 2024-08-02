<?php

namespace App\Livewire\Traits;

trait DataModifiedSaved
{

    public bool $dataModified = false;
    public bool $dataSaved = false;

    /**
     * Set all properties to their default values;
     *
     * @return void
     */
    function resetDataModifiedSaved(): void
    {
        $this->dataModified = false;
        $this->dataSaved = false;
    }

    function setDataModified(bool $bool = true): void
    {
        $this->dataModified = $bool;
        $this->dataSaved = !$bool;
    }

    function setDataSaved(bool $bool = true): void
    {
        $this->dataSaved = $bool;
        $this->dataModified = !$bool;
    }

    function getDataModified(): bool
    {
        return $this->dataModified;
    }

    function getDataSaved(): bool
    {
        return $this->dataSaved;
    }
}
