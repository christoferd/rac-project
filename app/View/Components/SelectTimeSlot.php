<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectTimeSlot extends Component
{
    public array $timeSlots = [];
    public string $selected = '0';

    /**
     * Create a new component instance.
     */
    public function __construct(string $selected = '0')
    {
        $this->selected = $selected;
        $this->timeSlots = config('rental.calendar_entry_time_slots');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-time-slot');
    }
}
