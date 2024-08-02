@props(['myModalId' => ''])
<div x-data="{showDeleteConfirm: false}">
    {{--<x-live.livewire-loading-spinner/>--}}

    {{--    Buttons--}}
    <div class="absolute right-6 top-16 pt-1 flex flex-col gap-3">
        <button type="button" class="bg-white p-1 hover:bg-gray-200 rounded z-modal-1-btn"
                x-on:click.stop="ig_increaseScale(0.2)">
            <x-heroicon-o-magnifying-glass-plus class="w-6 h-6"/>
        </button>
        <button type="button" class="bg-white p-1 hover:bg-gray-200 rounded z-modal-1-btn"
                x-on:click.stop="ig_decreaseScale(0.2)">
            <x-heroicon-o-magnifying-glass-minus class="w-6 h-6"/>
        </button>
    </div>

    {{--    Delete Button--}}
    <div class="absolute right-6 bottom-8 pt-1 flex flex-col gap-3 z-confirm">
        <div class="flex items-center justify-start gap-1">
            <button type="button" class="bg-white p-1 hover:bg-gray-200 rounded z-modal-1-btn" title="Delete"
                    x-on:click.stop="showDeleteConfirm=!showDeleteConfirm"
            >
                <x-heroicon-o-trash class="w-6 h-6 text-red-800"/>
            </button>
            <div x-show="showDeleteConfirm" class="bg-white p-1 px-2"
                 x-ref="confirmDelete"
            >
                <div class="flex gap-1">
                    <span>{!! __t('Confirm', 'Delete', 'File') !!}</span>
                    {{--Confirm--}}
                    <button type="button" class="text-sm"
                        x-on:click.stop="showDeleteConfirm=!showDeleteConfirm; ig_deleteFile(); closeMyModal('{!! $myModalId !!}')"
                    >
                        <x-heroicon-o-check class="w-6 h-6 text-red-800"/>
                    </button>
                    {{--Cancel--}}
                    <button type="button" class="text-sm text-gray-700"
                            x-on:click.stop="showDeleteConfirm=!showDeleteConfirm"
                    >
                        <x-heroicon-o-x-mark class="w-6 h-6"/>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div style="width: 100%; height: 90vh; border: 4px solid white;"
         class="overflow-hidden bg-gray-300">
        <img id="ig_hit_area" src="" alt="image"
             style="width: 100%; max-width: 10000px;">
    </div>
    <script type="text/javascript">

        var ig_el = null;
        var initScale = 1;
        var ig_START_X = 0;
        var ig_START_Y = 0;
        // the index of the mediaCollection (see ModelFilesManager)
        var media_index = null;

        if(ticking !== undefined) {
            console.warn('WARNING! ticking variable already exists. (image-gallery)')
        }
        var ticking = false;

        if(transform !== undefined) {
            console.warn('WARNING! transform variable already exists. (image-gallery)')
        }
        var transform;

        // if(timer !== undefined) {
        //     console.warn('WARNING! timer variable already exists. (image-gallery)')
        // }
        // var timer;

        /**
         * Most of this code is from hammer.js website
         * kind of messy code, but good enough for now
         * // polyfill
         */
        var reqAnimationFrame = null;

        function ig_logEvent(ev) {
            log('ev.type=' + ev.type);
        }

        function ig_resetElement() {
            log('> ig_resetElement()')
            ticking = false;
            // ig_el.className = 'animate';
            transform = {
                translate: {
                    x: 0,
                    y: 0
                },
                scale: 1,
                angle: 0,
                rx: 0,
                ry: 0,
                rz: 0
            };
            ig_requestElementUpdate();
        }

        function ig_increaseScale(f) {
            transform.scale += (transform.scale * f);
            ig_updateElementTransform();
        }

        function ig_decreaseScale(f) {
            transform.scale -= (transform.scale * f);
            ig_updateElementTransform();
        }

        function ig_updateElementTransform() {
            log('ig_updateElementTransform');
            var value = [
                'translate3d(' + transform.translate.x + 'px, ' + transform.translate.y + 'px, 0)',
                'scale(' + transform.scale + ', ' + transform.scale + ')',
                // 'rotate3d(' + transform.rx + ',' + transform.ry + ',' + transform.rz + ',' + transform.angle + 'deg)'
            ];

            value = value.join(" ");
            ig_el.style.webkitTransform = value;
            ig_el.style.mozTransform = value;
            ig_el.style.transform = value;
            ticking = false;
        }

        function ig_requestElementUpdate() {
            log('> ig_requestElementUpdate');
            if(!ticking) {
                reqAnimationFrame(ig_updateElementTransform);
                ticking = true;
            }
        }

        function onHammerInput(ev) {
            if(ev.isFinal) {
                log('X ev.isFinal (ig_hm.on "hammer.input")')
                log('ev.type=' + ev.type)
                log('ev.deltaX=' + ev.deltaX)
                log('ev.deltaY=' + ev.deltaY)
                ig_START_X += ev.deltaX;
                ig_START_Y += ev.deltaY;
                // ig_resetElement();
            }
        }

        function onPan(ev) {
            if(ev.type === 'panstart') {
                log('## Caught panstart')
                // ig_START_X = ig_el.offsetLeft;
                // ig_START_Y = ig_el.offsetTop;
                log('ig_START_X =' + ig_START_X);
                log('ig_START_Y =' + ig_START_Y);
            }

            // ig_el.className = '';
            transform.translate = {
                x: ig_START_X + ev.deltaX,
                y: ig_START_Y + ev.deltaY
            };

            ig_logEvent(ev);
            ig_requestElementUpdate();
        }

        function onPinch(ev) {
            if(ev.type === 'pinchstart') {
                initScale = transform.scale || 1;
            }

            // ig_el.className = '';
            transform.scale = initScale * ev.scale;

            ig_logEvent(ev);
            ig_requestElementUpdate();
        }

        // var initAngle = 0;

        // function onRotate(ev) {
        //     if(ev.type == 'rotatestart') {
        //         initAngle = transform.angle || 0;
        //     }
        //
        //     ig_el.className = '';
        //     transform.rz = 1;
        //     transform.angle = initAngle + ev.rotation;
        //
        //     ig_logEvent(ev);
        //     ig_requestElementUpdate();
        // }

        // function onSwipe(ev) {
        //     var angle = 50;
        //     transform.ry = (ev.direction & Hammer.DIRECTION_HORIZONTAL) ? 1 : 0;
        //     transform.rx = (ev.direction & Hammer.DIRECTION_VERTICAL) ? 1 : 0;
        //     transform.angle = (ev.direction & (Hammer.DIRECTION_RIGHT | Hammer.DIRECTION_UP)) ? angle : -angle;
        //
        //     clearTimeout(timer);
        //     timer = setTimeout(function() {
        //         ig_resetElement();
        //     }, 300);
        //
        //     ig_logEvent(ev);
        //     ig_requestElementUpdate();
        // }
        //
        // function onTap(ev) {
        //     transform.rx = 1;
        //     transform.angle = 25;
        //
        //     clearTimeout(timer);
        //     timer = setTimeout(function() {
        //         ig_resetElement();
        //     }, 200);
        //
        //     ig_logEvent(ev);
        //     ig_requestElementUpdate();
        // }

        // function onDoubleTap(ev) {

        // transform.scale = (transform.scale===2?1:2);
        // // log('#DT ig_START_X = '+ig_START_X);
        // // log('#DT ig_START_Y = '+ig_START_Y);
        //
        // // Image XY rel to page
        // let imgRect = ig_el.getBoundingClientRect();
        // log('#DT img x = ' + imgRect.x);
        // log('#DT img y = ' + imgRect.y);
        //
        // log('#DT mouse page x = ' + ev.center.x);
        // log('#DT mouse page y = ' + ev.center.y);
        //
        // let mouseImgX = (ev.center.x - imgRect.x);
        // let mouseImgY = (ev.center.y - imgRect.y);
        //
        // log('#DT mouse img x = ' + mouseImgX);
        // log('#DT mouse img y = ' + mouseImgY);

        //     ig_logEvent(ev);
        //     ig_requestElementUpdate();
        // }

        function ig_loadImage(imgUrl, mediaIndex) {
            log('> ig_loadImage(imgUrl, mediaIndex)', imgUrl, mediaIndex);
            // ig_resetElement();
            media_index = mediaIndex;
            transform.scale = 1;
            // ig_updateElementTransform(); // FORCED!!!
            log('*** ig_loadImage(imgUrl) Setting image src now...');
            ig_el.src = imgUrl;
            ig_resetElement();
            log('< END ig_loadImage(imgUrl)');
        }

        var ig_hm = null;

        function loadAnimationFrame() {
            log('> loadAnimationFrame()');

            reqAnimationFrame = (function() {
                return window[Hammer.prefixed(window, 'requestAnimationFrame')] || function(callback) {
                    window.setTimeout(callback, 1000 / 60);
                };
            })();

            ig_hm = new Hammer.Manager(ig_el);

            ig_hm.add(new Hammer.Pan({
                threshold: 0,
                pointers: 0
            }));

            // Swipe pan
            // ig_hm.add(new Hammer.Swipe()).recognizeWith(ig_hm.get('pan'));

            // Rotate
            // ig_hm.add(new Hammer.Rotate({
            //     threshold: 0
            // })).recognizeWith(ig_hm.get('pan'));

            // Pinch
            ig_hm.add(new Hammer.Pinch({
                threshold: 0
            })).recognizeWith([ig_hm.get('pan')]); //, ig_hm.get('rotate')

            // Tap Double-tap
            // ig_hm.add(new Hammer.Tap({
            //     event: 'doubletap',
            //     taps: 2
            // }));

            // Tap
            // ig_hm.add(new Hammer.Tap());

            // Connect actions to functions
            ig_hm.on("panstart panmove", onPan);
            ig_hm.on("pinchstart pinchmove", onPinch);
            // ig_hm.on("rotatestart rotatemove", onRotate);
            // ig_hm.on("swipe", onSwipe);
            // ig_hm.on("tap", onTap);
            // ig_hm.on("doubletap", onDoubleTap);

            ig_hm.on("hammer.input", onHammerInput);

            ig_resetElement();
        }

        /*
         * Used when dispatching Livewire events.
         */
        var ig_modelClass = '';
        var ig_modelId = 0;

        function ig_setModelClassId(mc, mid) {
            ig_modelClass = mc;
            ig_modelId = mid;
        }

        function ig_deleteFile() {
            if(ig_modelClass !== '' && ig_modelId !== 0) {
                Livewire.dispatch('DeleteFile', [ig_modelClass, ig_modelId, media_index]);
                return;
            }
            console.error('! ig_modelClass / ig_modelId is empty ! in function ig_deleteImage()')
        }

        /*
         * DOM Ready?!
         */
        document.addEventListener("DOMContentLoaded", function(event) {

            log('DOMContentLoaded in views/components/image-gallery.blade.php')

            // var screen = document.querySelector(".device-screen");
            ig_el = document.querySelector("#ig_hit_area");
            log('X pos = ' + ig_el.offsetLeft);
            log('Y pos = ' + ig_el.offsetTop);

            loadAnimationFrame();
        });
    </script>
</div>
