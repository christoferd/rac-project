<?php

namespace App\Livewire;

use App\Models\RentalTask;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class AllTasksCompleteIcon extends ObjectiveComponent
{
    protected $listeners = [
        'ToggleCompletedTask' => 'loadData'
    ];

    public bool $allTasksComplete = false;
    /**
     * @var string|RentalTask Adding models here to keep IDE checker happy.
     */
    public string $modelClass = '';
    public string $cssClass = '';
    public int $modelId = 0;
    public int $groupNum = 0;

    public function mount(string $modelClass, string $cssClass, int $modelId, int $groupNum): void
    {
        $this->cssClass = $cssClass;
        $this->modelClass = $modelClass;
        $this->modelId = $modelId;
        $this->groupNum = $groupNum;
    }

    public function loadData(): void
    {
        // Add tasks complete info
        if($this->modelId && $this->groupNum) {
            Log::debug(__CLASS__.' function loadData()');
            // $this->allTasksComplete = $this->modelClass::hasCompletedAllTasks($this->modelId, $this->groupNum);
            dd('// Chris D. 9-Apr-2024');
        }
    }

    public function render(): View
    {
        return view('livewire.all-tasks-complete-icon');
    }
}
