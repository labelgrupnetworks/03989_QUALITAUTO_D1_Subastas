@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('framework-css')
    <link type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
@endsection

@section('custom-css')
    <link type="text/css" href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet">
    <link type="text/css" href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet">
@endsection

@push('scripts')
    <script src="{{ URL::asset('js/openseadragon.min.js') }}"></script>
    <script src="{{ Tools::urlAssetsCache('/js/default/articles.js') }}"></script>
    <script>
        var logged = {{ Session::has('user') ? 'true' : 'false' }};
        var lang = '{{ \Config::get('app.locale') }}';
    </script>
@endpush

@php
    $bread = [];
    if (!empty(\Config::get('app.uniqueArtCategory'))) {
        #hace falta tener en links la traducción montada con el nombre de la categoria y _category
        $bread[] = [
            'url' => route('articles-category', [
                'category' => trans($theme . '-app.links.' . \Config::get('app.uniqueArtCategory') . '_category'),
            ]),
            'name' => trans($theme . '-app.articles.articles'),
        ];
    } else {
        $bread[] = ['url' => Route('articles'), 'name' => trans($theme . '-app.articles.articles')];
    }
    $bread[] = ['name' => $article->model_art0];
	$menuEstaticoHtml = (new App\Services\Content\PageService())->getPage('MENUJOYERIA');
@endphp

@section('content')
    {!! $menuEstaticoHtml->content_web_page !!}

    <main class="articles-ficha">
        <div class="container bread-wrapper">
            @include('includes.bread')
        </div>

        @include('content.articles.article')
    </main>
    @include('includes.whatsapp_button')
@stop
