<?php

namespace App\Traits;

trait AlternatingStringsTrait
{
    protected int $acAlternatingStringsIndex = 0;

    public array $acAlternatingStrings = [];

    /**
     * Will only return the colour at the current position.
     * This will not move the index.
     *
     * @return string
     */
    public function getCurrentString(): string
    {
        return $this->acAlternatingStrings[$this->acAlternatingStringsIndex];
    }

    /**
     * Get colour at current index, and set index to the next colour.
     *
     * @return string
     */
    public function getNextString(): string
    {
        $this->incrementAcStringTablesIndex();
        return $this->getCurrentString();
    }

    /**
     * Get colour depending on a number.
     *
     * @param int $number
     * @param int $colourTable Default: -1 = automatically switch between available tables
     * @return string
     */
    // public function pickStringForId(int $number, int $colourTable = (-1)): string
    // {
    //     if($colourTable >= 0) {
    //         $this->acStringTablesIndex = $colourTable;
    //     }
    //     else {
    //         // auto cycle table index
    //         $this->incrementAcStringTablesIndex();
    //     }
    //     $count = count($this->acStringTables[$this->acStringTablesIndex]);
    //     return $this->acStringTables[$this->acStringTablesIndex][($number % $count)].'-'.$this->alternatingStringsIntensity;
    // }

    /**
     * Will increment the property, and cycle back to 0 if needed.
     *
     * @return int Return $this->acStringTablesIndex after incrementing
     */
    public function incrementAcStringTablesIndex(): int
    {
        $this->acAlternatingStringsIndex++;
        // check if reached end
        if($this->acAlternatingStringsIndex === count($this->acAlternatingStrings)) {
            // reset
            $this->acAlternatingStringsIndex = 0;
        }
        return $this->acAlternatingStringsIndex;
    }
}
