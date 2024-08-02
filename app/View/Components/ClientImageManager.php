<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientImageManager extends Component
{
    public int $clientId = 0;

    public function __construct(int $clientId = 0)
    {
        $this->clientId = $clientId;
    }

    public function render(): View
    {
        return view('components.client-image-manager');
    }
}
