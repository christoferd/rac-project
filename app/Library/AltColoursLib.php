<?php

namespace App\Library;

use App\Traits\AlternatingStringsTrait;

class AltColoursLib
{
    use AlternatingStringsTrait;

    public function __construct(array $colours)
    {
        $this->acAlternatingStrings = $colours;
    }
}
