<?php

namespace App\Models\Traits;

trait ActiveFieldModelTrait
{
    protected string $activeFieldName = 'active';

    // constants in traits only allowed from PHP v8.2
    static string $ACTIVE_YES = '1';

    function setActiveYes(): void
    {
        $f =& $this->activeFieldName;
        $this->$$f = static::$ACTIVE_YES;
    }

    function setActiveNo(): void
    {
        $f =& $this->activeFieldName;
        $this->$$f = static::$ACTIVE_YES;
    }
}
