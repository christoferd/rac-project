import './bootstrap.js';

// Chris D. 5-Feb-2024 It imports the script, but for some reason I can't access the methods from front end.
// e.g. "openOffCanvas is not defined"
// import '../../public/assets/js/custom.js';

// Chris D. Livewire 3 upgrade
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
// Chris D. 5-Feb-2024 REMOVED during livewire 3 upgrade
// import Alpine from 'alpinejs';
// import focus from '@alpinejs/focus';
// Alpine.plugin(focus);
// Livewire 3 now ships with the following Alpine plugins out-of-the-box:
// Alpine: Collapse
// Alpine: Focus
// Alpine: Intersect
// Alpine: Mask
// Alpine: Morph
// Alpine: Persist

// import { Offcanvas, Dropdown, initTE } from "tw-elements";
// initTE({ Offcanvas, Dropdown });

import('preline');

// Chris D. 5-Feb-2024 Not working as expected
// import HSSelect from '@preline/select/';
// window.HSSelect = HSSelect;

window.Alpine = Alpine;

// Tippy
import Tooltip from "@ryangjchandler/alpine-tooltip";
Alpine.plugin(Tooltip);
// Focus
// import focus from "@alpinejs/focus"; // @todo Is this required with Livewire 3 - it may already be importing it?
// Alpine.plugin(focus);

// Chris D. 5-Feb-2024 REMOVED during livewire 3 upgrade
// Alpine.start();
Livewire.start() // v3

// Chris D. 4-Mar-2024
// import Spotlight from "../../node_modules/spotlight.js/src/js/spotlight.js";
// window.Spotlight = Spotlight;

// Chris D. 5-Mar-2024
import 'hammerjs';
