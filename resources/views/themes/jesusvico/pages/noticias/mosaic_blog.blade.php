@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    <main class="museum-page">

        <div class="container">

            <h1>{{ trans("$theme-app.blog.museum-pieces") }}</h1>

            <div class="row row-cols-1 row-cols-md-2 gy-3 gx-3">
                @foreach ($banners as $key => $banner)
                    @php
                        $year = '';
                        if (Str::contains($banner->descripcion, '(')) {
                            $year = Str::between($banner->descripcion, '(', ')');
                            $banner->descripcion = Str::beforeLast($banner->descripcion, ' (');
                        }
                    @endphp

                    <article class="col">
                        <div class="card museum-card h-100" id="card_{{$key}}">
                            <img class="card-img-top" src="{{ $banner->url_image }}" alt="{{ $banner->descripcion }}"
                                loading="{{ $loop->iteration > 6 ? 'lazy' : 'auto' }}">

                            <div class="card-body pb-0">
                                <h2 class="card-title">{{ $banner->descripcion }}</h2>
                                <h5 class="card-year text-lb-gray">{{ $year }}</h5>
                                <h4 class="card-description fw-lighter">{!! strip_tags($banner->texto) !!}</h4>
                            </div>


                            <div class="card-footer bg-transparent border-0 p-0">
                                <button class="btn btn-lb-primary px-4 float-end" data-bs-toggle="modal"
                                    data-bs-target="#museumModal" data-bs-key="{{ $key }}">
                                    @include('components.boostrap_icon', [
                                        'icon' => 'plus-circle',
                                        'size' => '24',
                                    ])
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

    </main>

    <div class="modal fade" id="museumModal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button class="btn-close btn-lb-close" data-bs-dismiss="modal" type="button" aria-label="Close">
						{{-- @include('components.boostrap_icon', ['icon' => 'x-circle']) --}}
					</button>
                </div>

                <div class="modal-body">
                    <img id="modal-img" class="w-100" src="" alt="">
                </div>

                <div class="modal-footer flex-column align-items-start border-0">
                    <h2 id="title"></h2>
                    <h5 id="year" class="text-lb-gray"></h5>
                    <h4 id="description" class="fw-lighter"></h4>
                </div>
            </div>
        </div>
    </div>

    <script>
        const museumModal = document.getElementById('museumModal')

		museumModal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget;
			const key = button.getAttribute('data-bs-key');

			const card = document.getElementById(`card_${key}`);

			const imageModal = museumModal.querySelector('#modal-img');
			const titleModal = museumModal.querySelector('#title');
			const yearModal = museumModal.querySelector('#year');
			const descriptionModal = museumModal.querySelector('#description');

			imageModal.src = card.querySelector('.card-img-top').src;
			titleModal.textContent = card.querySelector('.card-title').textContent;
			yearModal.textContent = card.querySelector('.card-year').textContent;
			descriptionModal.textContent = card.querySelector('.card-description').textContent;
        })
    </script>
@stop
