@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('seo')
    @php
        $data['seo'] = new \stdClass();
        $data['seo']->meta_title = trans(\Config::get('app.theme') . '-app.metas.title_events');
        $data['seo']->meta_description = trans(\Config::get('app.theme') . '-app.metas.description_events');
    @endphp
@endsection

@section('content')

    <main class="events-page">

        <div class="container">
            <h1 class="mb-5">{{ trans("$theme-app.blog.events") }}</h1>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                @foreach ($banners as $banner)
                    <div class="col">
                        <article class="card event-card border-0 text-center mb-5">
                            <a class="stretched-link" href="/{{ config('app.locale') }}/events/{{ $banner->id }}"></a>

                            <div class="img-card-wrapper" style="background-image: url('{{ $banner->url_image }}')"></div>

                            <div class="card-body p-4">
                                <h2>{{ $banner->descripcion }}</h2>
                                <h4 class="card-description fw-lighter">{!! strip_tags($banner->texto) !!}</h4>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

        </div>
    </main>
@stop
