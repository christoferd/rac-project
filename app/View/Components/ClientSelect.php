<?php

namespace App\View\Components;

use App\Models\Client;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class ClientSelect extends Component
{
    public Collection $clients;
    public string $targetComponent;

    /**
     * Create a new component instance.
     */
    public function __construct(string $targetComponent)
    {
        $this->targetComponent = $targetComponent;
        $this->clients = collect(
            Client::orderBy('name')
                  ->get(['id', 'name'])); // 4kb
        // $this->clients = Client::orderBy('name')->get(['id', 'name'])->toArray(); // 4kb too! ?:-/
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client-select');
    }
}
