<div>
    <div>
        <x-button wire:click="$dispatch('DemoAlerts')">
            Demo
        </x-button>
        <x-button wire:click="$dispatch('AddAlert', ['error', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.'])">
            Error
        </x-button>
        <x-button wire:click="$dispatch('AddAlert', ['success', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.'])">
            Success
        </x-button>
        <x-button wire:click="$dispatch('AddAlert', ['warning', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.'])">
            Warning
        </x-button>
        <x-button wire:click="$dispatch('AddAlert', ['info', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.'])">
            Info
        </x-button>
    </div>
</div>
