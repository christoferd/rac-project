@push('scripts')
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"--}}
{{--            integrity="sha512-UXumZrZNiOwnTcZSHLOfcTs0aos2MzBWHXOHOuB0J/R44QB0dwY5JgfbvljXcklVf65Gc4El6RjZ+lnwd2az2g==" crossorigin="anonymous"--}}
{{--            referrerpolicy="no-referrer"></script>--}}
    <script type="text/javascript">

        document.addEventListener("DOMContentLoaded", function(event) {

            /**
             * Most of this code is from hammer.js website
             * kind of messy code, but good enough for now
             */
                // polyfill
            var reqAnimationFrame = (function() {
                    return window[Hammer.prefixed(window, 'requestAnimationFrame')] || function(callback) {
                        window.setTimeout(callback, 1000 / 60);
                    };
                })();

            var screen = document.querySelector(".device-screen");
            var el = document.querySelector("#hitarea");
            console.info('X pos = '+el.offsetLeft);
            console.info('Y pos = '+el.offsetTop);

            var START_X = 0; //Math.round((screen.offsetWidth - el.offsetWidth) / 2);
            var START_Y = 0; //Math.round((screen.offsetHeight - el.offsetHeight) / 2);

            var ticking = false;
            var transform;
            var timer;

            var mc = new Hammer.Manager(el);

            mc.add(new Hammer.Pan({
                threshold: 0,
                pointers: 0
            }));

            mc.add(new Hammer.Swipe()).recognizeWith(mc.get('pan'));
            mc.add(new Hammer.Rotate({
                threshold: 0
            })).recognizeWith(mc.get('pan'));
            mc.add(new Hammer.Pinch({
                threshold: 0
            })).recognizeWith([mc.get('pan'), mc.get('rotate')]);

            mc.add(new Hammer.Tap({
                event: 'doubletap',
                taps: 2
            }));
            mc.add(new Hammer.Tap());

            mc.on("panstart panmove", onPan);
            /* mc.on("rotatestart rotatemove", onRotate); */
            mc.on("pinchstart pinchmove", onPinch);
            /* mc.on("swipe", onSwipe); */
            /* mc.on("tap", onTap); */
            mc.on("doubletap", onDoubleTap);

            mc.on("hammer.input", function(ev) {
                if(ev.isFinal) {
                    console.info('X ev.isFinal (mc.on "hammer.input")')
                    console.info('ev.type='+ev.type)
                    console.info('ev.deltaX='+ev.deltaX)
                    console.info('ev.deltaY='+ev.deltaY)
                    START_X += ev.deltaX;
                    START_Y += ev.deltaY;
                    // resetElement();
                }
            });

            function logEvent(ev) {
                console.info('ev.type=' + ev.type);
            }

            function resetElement() {
                console.info('> resetElement()')
                // el.className = 'animate';
                transform = {
                    translate: {
                        x: START_X,
                        y: START_Y
                    },
                    scale: 1,
                    angle: 0,
                    rx: 0,
                    ry: 0,
                    rz: 0
                };
                requestElementUpdate();
            }

            function updateElementTransform() {
                var value = [
                    'translate3d(' + transform.translate.x + 'px, ' + transform.translate.y + 'px, 0)',
                    'scale(' + transform.scale + ', ' + transform.scale + ')',
                    // 'rotate3d(' + transform.rx + ',' + transform.ry + ',' + transform.rz + ',' + transform.angle + 'deg)'
                ];

                value = value.join(" ");
                el.style.webkitTransform = value;
                el.style.mozTransform = value;
                el.style.transform = value;
                ticking = false;
            }

            function requestElementUpdate() {
                if(!ticking) {
                    reqAnimationFrame(updateElementTransform);
                    ticking = true;
                }
            }

            function onPan(ev) {
                if(ev.type === 'panstart') {
                    console.info('## Caught panstart')
                    // START_X = el.offsetLeft;
                    // START_Y = el.offsetTop;
                    console.info('START_X ='+START_X);
                    console.info('START_Y ='+START_Y);
                }

                // el.className = '';
                transform.translate = {
                    x: START_X + ev.deltaX,
                    y: START_Y + ev.deltaY
                };

                logEvent(ev);
                requestElementUpdate();
            }

            var initScale = 1;

            function onPinch(ev) {
                if(ev.type === 'pinchstart') {
                    initScale = transform.scale || 1;
                }

                // el.className = '';
                transform.scale = initScale * ev.scale;

                logEvent(ev);
                requestElementUpdate();
            }

            var initAngle = 0;

            // function onRotate(ev) {
            //     if(ev.type == 'rotatestart') {
            //         initAngle = transform.angle || 0;
            //     }
            //
            //     el.className = '';
            //     transform.rz = 1;
            //     transform.angle = initAngle + ev.rotation;
            //
            //     logEvent(ev);
            //     requestElementUpdate();
            // }

            // function onSwipe(ev) {
            //     var angle = 50;
            //     transform.ry = (ev.direction & Hammer.DIRECTION_HORIZONTAL) ? 1 : 0;
            //     transform.rx = (ev.direction & Hammer.DIRECTION_VERTICAL) ? 1 : 0;
            //     transform.angle = (ev.direction & (Hammer.DIRECTION_RIGHT | Hammer.DIRECTION_UP)) ? angle : -angle;
            //
            //     clearTimeout(timer);
            //     timer = setTimeout(function() {
            //         resetElement();
            //     }, 300);
            //
            //     logEvent(ev);
            //     requestElementUpdate();
            // }
            //
            // function onTap(ev) {
            //     transform.rx = 1;
            //     transform.angle = 25;
            //
            //     clearTimeout(timer);
            //     timer = setTimeout(function() {
            //         resetElement();
            //     }, 200);
            //
            //     logEvent(ev);
            //     requestElementUpdate();
            // }

            function onDoubleTap(ev) {

                // transform.scale = (transform.scale===2?1:2);
                // // console.info('#DT START_X = '+START_X);
                // // console.info('#DT START_Y = '+START_Y);
                //
                // // Image XY rel to page
                // let imgRect = el.getBoundingClientRect();
                // console.info('#DT img x = ' + imgRect.x);
                // console.info('#DT img y = ' + imgRect.y);
                //
                // console.info('#DT mouse page x = ' + ev.center.x);
                // console.info('#DT mouse page y = ' + ev.center.y);
                //
                // let mouseImgX = (ev.center.x - imgRect.x);
                // let mouseImgY = (ev.center.y - imgRect.y);
                //
                // console.info('#DT mouse img x = ' + mouseImgX);
                // console.info('#DT mouse img y = ' + mouseImgY);

                logEvent(ev);
                requestElementUpdate();
            }

            resetElement();
        });
    </script>
@endpush
<x-app-layout>
    <x-slot name="pageTitle">
        test-hammerjs-image-mobile
    </x-slot>
    <div style="width: 100%; height: 80vh; overflow: hidden; border: 4px solid white; background-color: #999;" class="device-screen relative">
        <img id="hitarea" src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Big_Bear_Valley,_California.jpg" alt="image"
             style="width: 100%; max-width: 20000px;">
    </div>
</x-app-layout>
