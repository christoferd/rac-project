@props(['id',
'shellClass' => 'fixed w-screen max-w-4xl left-0 top-0 p-1 qs:p-2 sm:p-4 w-screen sm:left-1/2 sm:-translate-x-1/2',
'panelClass' => 'p-2 sm:p-4',
'resetZoomOnClose' => false
])
<div id="ModalOverlay{!! $id !!}" tabindex="-1"
     {{--800vh will fix ISSUE: Overlay not covering whole screen WHEN zoom out on mobile--}}
     class="my-modal-overlay height: 800vh; width: 800vh; hidden z-modal-1-overlay fixed top-0 left-0 overflow-x-hidden overflow-y-auto"
>
</div>
{{--https://www.kindacode.com/snippet/tailwind-css-how-to-center-a-fixed-element/--}}
<div id="Modal{!! $id !!}"
     x-data="{
        myId: '{!! $id !!}',
        closeMe: function(refStr) {
            if(!this.$refs.myModalTopShell.classList.contains('hidden')) {
                log('!!! MyModal: '+this.myId+' '+refStr+' !!!');
                closeMyModal(this.myId);
                resetDocumentBodyZoom();
            }
        }
     }"
     x-ref="myModalTopShell"
     class="hidden my-modal-shell z-modal-1 bg-transparent {!! $shellClass?:'' !!}">
    <div class="my-modal-panel bg-white border rounded shadow-2xl {!! $panelClass?:'' !!}"
         x-on:click.away.stop="closeMe('click.away')"
         x-on:keydown.escape.window="closeMe('keydown.escape')"
    >
        <button class="close absolute right-6 top-6 bg-white p-1 hover:bg-gray-200 rounded z-modal-1-btn"
                x-on:click.stop="closeMe('clicked close button')">
            <x-heroicon-o-x-mark class="w-6 h-6"/>
        </button>
        {!! $slot !!}
    </div>
</div>
