@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')

    <main class="event-page">

        <section class="container">
            <h1>{{ trans("$theme-app.blog.events") }}</h1>
            <h2>{{ $banner->descripcion }}</h2>
            <h5>{{ head($banner->images) }}</h5>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 gy-3 gx-3 mt-5">
                @foreach ($banner->images as $id => $text)
                    <div class="col event-images-wrapper position-relative cursor-pointer" id="event_{{ $id }}">
                        <a class="stretched-link" data-bs-toggle="modal" data-bs-target="#eventModal"
                            data-bs-id="{{ $id }}"></a>
                        <img class="event-popup-img"
                            src="/img/banner/{{ $theme }}/{{ \Config::get('app.emp') }}/{{ $banner->id }}/{{ $id }}/ES.jpg"
                            alt="{{ $text }}">
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <div class="modal fade" id="eventModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button class="btn-close btn-lb-close" data-bs-dismiss="modal" type="button"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <img class="w-100" id="modal-img" src="" alt="">
                </div>
            </div>
        </div>
    </div>

    <script>
        const eventModal = document.getElementById('eventModal')

        eventModal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-id');
            const eventCol = document.getElementById(`event_${id}`);

            const imageModal = eventModal.querySelector('#modal-img');
			imageModal.src = eventCol.querySelector('img').src;
        })
    </script>

@stop
