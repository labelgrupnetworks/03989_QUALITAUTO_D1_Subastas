@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')
    @php
        $bread[] = ['name' => $data['data']->name_web_page];
    @endphp

    <main class="static">

        {!! BannerLib::bannerWithView('static-pages', 'hero', [
            'title' => $data['data']->name_web_page,
            'breadcrumb' => view('includes.breadcrumb', ['bread' => $bread])->render(),
        ]) !!}

        <div class="container mt-5">
            <div class="contenido contenido-web static-page" id="pagina-{{ $data['data']->id_web_page }}" data-page-key="{{ $data['data']->key_web_page }}">
                {!! $data['data']->content_web_page !!}
            </div>
        </div>
    </main>

@stop
