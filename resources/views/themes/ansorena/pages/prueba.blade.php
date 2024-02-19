@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

    <style>
        .h-block {
            overflow: hidden;
        }

        .other {
            height: 600px;
            background: gainsboro;
        }

        .h-scroll {
            height: 900px;
            display: flex;
            gap: 300px;
            align-items: center;
            overflow-x: scroll;
			background: aliceblue;
        }

        .h-scroll::-webkit-scrollbar {
            display: none;
        }

        .h-scroll * {
            background: blue;
            color: white;
        }
    </style>

    {!! BannerLib::bannerWithView('home-top-banner', 'syncing') !!}
	{!! BannerLib::bannerWithView('home-top-banner', 'syncing') !!}
	{!! BannerLib::bannerWithView('home-top-banner', 'syncing') !!}

    <div class="h-block">
        <div class="other">Superior</div>
		<div class="progress">
			<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
        <div class="h-scroll">
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
            <p>prueba</p>
        </div>
        <div class="other">inferior</div>
    </div>

    <script>
        function transformScroll(event) {
            if (!event.deltaY) {
                return;
            }
            const horizontalElement = event.currentTarget;
            const isGoingUp = event.deltaY < 0 && horizontalElement.scrollLeft === 0;
			const maxWith = Math.floor(horizontalElement.scrollWidth) - Math.floor(horizontalElement.clientWidth);
			const actualScroll = Math.floor(horizontalElement.scrollLeft);
            const isGoingDown = event.deltaY > 0 && maxWith === actualScroll;

			const progressBarElement = document.querySelector('.progress-bar');
			const percentProgress = Math.ceil((actualScroll * 100) / maxWith);
			progressBarElement.style.width = `${percentProgress}%`;

            if (isGoingUp || isGoingDown) {
                return;
            }

            event.currentTarget.scrollLeft += event.deltaY;
            event.preventDefault();
        }

        const element = document.querySelector('.h-scroll');
        element.addEventListener("wheel", transformScroll);

        // --------------- demo code
        /*  const statusElement = document.querySelector("div");

         function logScroll(event) {

             statusElement.innerText = element.scrollLeft;
         }
         window.addEventListener("scroll", logScroll); */
    </script>

@stop
