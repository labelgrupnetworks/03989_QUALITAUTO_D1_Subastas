@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@php
    $isDeparmentsPage = in_array($data['data']->key_web_page, ['departamentos', 'departments']);
@endphp

@section('content')

    <main class="static-page">
        <div class="contenido" id="pagina-{{ $data['data']->id_web_page }}">

            @if ($isDeparmentsPage)
                <div class="banner-home-row-departamentos">
                    {!! \BannerLib::bannersPorKey(
                        'home_departamentos',
                        'home-banner-departamentos',
                        '{dots:false, arrows:false,
                    			autoplay: true,
                    			autoplaySpeed: 4000, slidesToScroll:1}',
                    ) !!}
                </div>
            @else
                {!! $data['data']->content_web_page !!}
            @endif

        </div>
    </main>


@stop
