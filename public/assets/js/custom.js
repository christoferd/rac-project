// Chris D. 21-Jul-2023
const DEBUG_LOG = false;
let hasDisconnectedSinceRefresh = false;

let rentalsData = {
    isOpenOffCanvas: false,
    openOffCanvasId: '',
    isOpenMyModal: false,
    openMyModalId: '',
    LiveConfirmModalEventName: '',
    LiveConfirmModalParamValue: ''
};

function log(mixed, mixed2 = null) {
    if(DEBUG_LOG) {
        if(mixed2 !== null) {
            console.debug(mixed, mixed2);
        }
        else {
            console.debug(mixed);
        }
    }
}

// Chris D. 18-Jul-2023
function loadDatePicker(selector, ref) {
    console.log('# JS loadDatePicker(selector, ref): ' + selector + ' | ' + ref);

    // Flatpickr
    flatpickr.l10ns.default.firstDayOfWeek = 1; // Monday
    flatpickr(selector,
        {
            altInput: true,
            // format of value seen by user
            altFormat: 'D. j F',
            allowInput: false,
            clickOpens: true,
            // format of value that is not seen
            dateFormat: 'Y-m-d',
            enableTime: false,
            mode: 'single',
            "locale": "es",
            onChange: function(selectedDates, dateStr, instance) {
                console.log('onChange selectedDates = ', selectedDates); // mysql date
                console.log('onChange dateStr = ', dateStr); // mysql date
                let entangle = instance.element.getAttribute('x-data-entangle');
                console.log('input orig entangle value = ' + entangle);
                Livewire.dispatch('DateSelected_' + ref, {"field": entangle, "mysqlDateString": dateStr});
            }
        });
}

function loadLCDatePickerNoMobile(selector, fieldname, component) {
    console.log('# JS loadLCDatePickerNoMobile(selector: ' + selector + ' | fieldname: ' + fieldname + ' | component: ' + component);

    // Flatpickr
    flatpickr.l10ns.default.firstDayOfWeek = 1; // Monday
    return flatpickr(selector,
        {
            // altInput: true,
            // format of value seen by user
            // altFormat: 'D. j F.',
            allowInput: false,
            // clickOpens: true,
            // format of value that is not seen
            dateFormat: 'Y-m-d',
            enableTime: false,
            mode: 'single',
            disableMobile: true,
            "locale": "es",
            onChange: function(selectedDates, dateStr, instance) {
                Livewire.dispatch('DateSelected_' + component, {"field": fieldname, "mysqlDateString": dateStr});
            }
        });
}

/**
 * @return 1 if class was added. 0 if class was removed.
 */
function toggleClass(el, c) {
    if(el.classList.contains(c)) {
        el.classList.remove(c);
        return 0;
    }
    el.classList.add(c);
    return 1;
}

/**
 * @return 1 if class was added. 0 if class was removed.
 */
Element.prototype.toggleClass = function(c) {
    return toggleClass(this, c);
}

/**
 * Open MY VERSION OF THE Preline UI CSS Offcanvas element
 * Useful when the standard way of doing it won't work!
 * // Not if open MyModal
 *
 * @param id
 */
function openOffCanvas(id) {
    log('# JS openOffCanvas(id): ' + id);
    // Not if open MyModal
    if(isOpenOffCanvas(id))
        return;
    // Allow the click away events to trigger before opening
    // click.away will try close the modal
    window.setTimeout(() => {
        let el = getDocumentElementValid(id);
        if(el) {
            log('openOffCanvas: ' + id);
            if(el.classList.contains('translate-x-0'))
                log('-- skip: ' + id);
            else {
                el.classList.add('translate-x-0');
                document.getElementById('ModalOverlay').classList.remove('hidden');
                rentalsData.isOpenOffCanvas = true;
                rentalsData.openOffCanvasId = id;
            }
            preventBodyScroll(true);
        }
        else log('-- OffCanvas ' + id + ' already open');
    }, 200);
}

function isOpenOffCanvas(id = '') {
    if(!rentalsData.isOpenOffCanvas)
        return false;
    if(id === '')
        return true;
    return (rentalsData.openOffCanvasId === id);
}

/**
 * Close MY VERSION OF THE Preline UI CSS Offcanvas element
 * Useful when the standard way of doing it won't work!
 * // Not if open MyModal
 */
function closeOffCanvas(id) {
    id = (id ?? rentalsData.openOffCanvasId);
    log('# JS closeOffCanvas( ' + (id) + ' )... ');
    if(id === undefined) {
        id = rentalsData.openOffCanvasId;
        log(' - ID: openOffCanvasId = ' + id);
    }
    // Check if it is a "Simple" off canvas (level 2)
    else if(id !== rentalsData.openOffCanvasId) {
        // assume trying to close an OffCanvas that was opened with Simple
        log('** assume trying to close an OffCanvas that was opened with Simple **');
        closeOffCanvasSimple(id);
        return;
    }
    // Check something open
    if(id === '')
        return;

    // Not if open MyModal
    if(isOpenMyModal()) {
        log('X not close OffCanvas when MyModal is open');
        return;
    }

    if(!isOpenOffCanvas(id)) {
        console.error('X !!! Called to close ' + id + ', but is not open!');
        return;
    }

    let el = getDocumentElementValid(id);
    if(el) {
        log('closeOffCanvas: ' + id);
        if(!el.classList.contains('translate-x-0'))
            log('-- skip: ' + id);
        else {
            el.classList.remove('translate-x-0');
            document.getElementById('ModalOverlay').classList.add('hidden');
            rentalsData.isOpenOffCanvas = false;
            rentalsData.openOffCanvasId = '';
            Livewire.dispatch('Closed_' + id);
        }
        preventBodyScroll(false);
        // Livewire
        log('Livewire.dispatch(ClosedOffcanvasPanel)');
        Livewire.dispatch('ClosedOffcanvasPanel');
    }
    else log('-- OffCanvas ' + id + ' already closed');
}

// Returns bool - False if a myModal is already open
function openMyModal(id) {
    log('# JS openMyModal(id): ' + id);
    if(isOpenMyModal()) {
        log('MyModal already open; RETURN false');
        return false;
    }

    // Allow the click away events to trigger before opening
    // click.away will try close the modal
    window.setTimeout(() => {
        log('openMyModal: ' + id);
        const el = getDocumentElementValid('Modal' + id);
        const elo = getDocumentElementValid('ModalOverlay' + id);
        if(el && elo) {
            if(!el.classList.contains('hidden'))
                log('-- skip ' + id + ' -- it is not hidden');
            else {
                log(' # remove hidden');
                el.classList.remove('hidden');
                elo.classList.remove('hidden');
                rentalsData.isOpenMyModal = true;
                rentalsData.openMyModalId = id;
            }
            preventBodyScroll(true);
        }
        else
            log('-- MyModal ' + id + ' already open');
    }, 200);
    return true;
}

function isOpenMyModal(id = '') {
    if(!rentalsData.isOpenMyModal)
        return false;
    if(id === '')
        return true;
    return (rentalsData.openMyModalId === id);
}

function closeMyModal(id) {
    id = (id ?? rentalsData.openMyModalId);
    log('# JS closeMyModal(id): ' + id);
    if(!isOpenMyModal())
        return;

    const el = getDocumentElementValid('Modal' + id);
    const elo = getDocumentElementValid('ModalOverlay' + id);
    if(el && elo) {
        if(el.classList.contains('hidden'))
            log('-- skip ' + id + ' -- it is already hidden');
        else {
            log(' # add hidden');
            el.classList.add('hidden');
            elo.classList.add('hidden');
            rentalsData.isOpenMyModal = false;
            rentalsData.openMyModalId = '';
            Livewire.dispatch('Closed_' + id);
        }
        preventBodyScroll(false);
    }
    else
        log('-- MyModal ' + id + ' not open');
}

function inputIsDirty(el) {
    if(el.hasAttribute('x-data-orig-length') || el.hasAttribute('x-data-orig-value')) {
        let res = (el.getAttribute('x-data-orig-length') !== el.value.length);
        log('(el name: ' + (el.name ? el.name : '!no-name!') + ') inputIsDirty? ' + (res ? 'Yes' : 'No'));
        return res;
    }
    return false;
}

function unhideElForXSecs(el, s = 1) {
    if(el.classList.contains('hidden'))
        el.classList.remove('hidden');
    window.setTimeout((el) => el.classList.add('hidden'));
}

function getDocumentElementValid(id) {
    let el = document.getElementById(id);
    if(el === undefined || el === null) {
        console.error('Unable to get document element with id: ' + id);
        console.trace();
    }
    return el;
}

/**
 * Livewire Confirmation Modal
 *
 * @param message Message to display in the modal.
 * @param eventName Used with Livewire.dispatch( "{eventName}", ...)
 * @param paramValue Optional: Used with Livewire.dispatch("{eventName}", { "{paramkey}": {paramValue})
 */
function openLiveConfirmModal(message, eventName, paramValue) {
    // @todo STOP USING THIS FOR JUST SHOWING A MESSAGE!!! Update so that first 2 params are required
    if(eventName === undefined || eventName === null) {
        getDocumentElementValid('ConfirmModalButtons').classList.add('hidden');
        rentalsData.LiveConfirmModalEventName = '';
        rentalsData.LiveConfirmModalParamValue = '';
    }
    else {
        getDocumentElementValid('ConfirmModalButtons').classList.remove('hidden');
        rentalsData.LiveConfirmModalEventName = eventName;
        rentalsData.LiveConfirmModalParamValue = paramValue;
    }
    getDocumentElementValid('ConfirmModalText').innerHTML = message;
    getDocumentElementValid('ConfirmModal').classList.remove('hidden');
    getDocumentElementValid('ConfirmModalOverlay').classList.remove('hidden');
}

function closeLiveConfirmModal() {
    getDocumentElementValid('ConfirmModal').classList.add('hidden');
    getDocumentElementValid('ConfirmModalOverlay').classList.add('hidden');
    getDocumentElementValid('ConfirmModalText').innerHTML = '';
    rentalsData.LiveConfirmModalEventName = '';
    rentalsData.LiveConfirmModalParamValue = '';
}

function confirmLiveConfirmModal() {
    if(rentalsData.LiveConfirmModalEventName !== '') {
        Livewire.dispatch(rentalsData.LiveConfirmModalEventName, [rentalsData.LiveConfirmModalParamValue]);
    }
    closeLiveConfirmModal();
}

function orientationChangeAutoReload() {
    if(window.addEventListener) {
        // Dev Note: This doesn't work properly when using DevTools and Dimensions: Responsive
        window.addEventListener("orientationchange", function() {
            log('! orientationchange !');
            // @todo Messages in here need to be localized. Can use lang array that is set with external js file.
            // alert('ES: Es posible que esta aplicación no funcione completamente cuando la pantalla está de lado. \n' +
            //     'EN: This application may not completely work when your screen is sideways.');
        });
    }
}

function preventBodyScroll(b) {
    if(b) {
        document.body.classList.add('modal-open');
        return;
    }
    // Do not enable body scroll if any modals are open
    if(isOpenMyModal() || isOpenOffCanvas()) {
        return;
    }
    document.body.classList.remove('modal-open');
}

function openOffCanvasSimple(id) {
    log('# JS openOffCanvasLevel2(id): ' + id);
    let el = getDocumentElementValid(id);
    if(el) el.classList.add('translate-x-0');
    else console.error('### INVALID element id ' + id);
}

function closeOffCanvasSimple(id) {
    log('# JS closeOffCanvasLevel2(id): ' + id);
    let el = getDocumentElementValid(id);
    if(el) el.classList.remove('translate-x-0');
    else console.error('### INVALID element id ' + id);
}

/*
   Adds path to the end of PROTOCOL//HOST
   path = /my-url-path/123
 */
function buildUrl(u) {
    return window.location.protocol + "//" + window.location.host + u;
}

/**
 * @returns {number} Returns the scrollHeight
 */
HTMLTextAreaElement.prototype.autoResizeHeight = function() {
    // console.log('-- prototype.autoResizeHeight');
    try { // avoid browser issues
        this.style.height = (this.scrollHeight + 2) + 'px';
        return this.scrollHeight;
    }
    catch(e) { /* skip */
        return 0;
    }
}

let defaultBodyZoom = (document.body.style.zoom ?? 2);

function resetDocumentBodyZoom() {
    log('ZZZ resetDocumentBodyZoom() ZZZ');
    document.body.style.zoom = defaultBodyZoom;
}

// Used to execute some code when an element becomes visible in the viewport.
// https://stackoverflow.com/a/66394121/252825
// Usage: onVisible(document.querySelector("#myElement"), () => console.log("it's visible"));
function onVisible(element, callback) {
  new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if(entry.intersectionRatio > 0) {
        callback(element);
        observer.disconnect();
      }
    });
  }).observe(element);
  // Chris D. 3-Jun-2024 Removed because we will always have a callback.
  // if(!callback) return new Promise(r => callback=r);
}

// -----------------
//    DOM Ready!
document.addEventListener('DOMContentLoaded', (event) => {
    log('! DOMContentLoaded - custom.js !');
    orientationChangeAutoReload();

    // Modal Overlay Click
    document.getElementById("ModalOverlay").addEventListener("click", function(e) {
        e.stopPropagation();
        closeOffCanvas();
        closeMyModal();
    });

    onVisible(document.querySelector(".disconnected-message"),
        () => {
            log("El .disconnected-message is visible!");
            hasDisconnectedSinceRefresh = true;
            getDocumentElementValid('DiscSLUM').classList.remove('hidden')
        });
});
