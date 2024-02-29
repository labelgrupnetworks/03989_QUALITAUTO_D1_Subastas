@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.foot.faq') }}
@stop

@section('framework-css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('vendor/bootstrap/5.2.0/css/bootstrap.min.css') }}">
@endsection

@section('framework-js')
    <script src="{{ URL::asset('vendor/bootstrap/5.2.0/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>
@endsection

@section('custom-css')
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/global.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ Tools::urlAssetsCache("/themes/$theme/css/style.css") }}" rel="stylesheet" type="text/css">

    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/header.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    <main class="faq-page gray-page">
        <h1 class="ff-highlight fs-32-40 text-center text-uppercase">
            {{ trans("$theme-app.foot.faq") }}
        </h1>

        <div class="container">
            @foreach ($data['cats'] as $cat)
                @if (!empty($cat->parent_faqcat) && !$cat->parent_faqcat == 0)
                    <h2 class="ff-highlight fs-24-32 mt-5"> {{ $cat->nombre_faqcat }} </h2>

                    @if (!empty($data['itemsCats'][$cat->cod_faqcat]))
                        <div class="accordion accordion-flush lb-accordion" id="accordion-{{ $cat->parent_faqcat }}">
                            @foreach ($data['itemsCats'][$cat->cod_faqcat] as $item)
                                <div class="accordion-item @if ($loop->index > 4) hideable-item d-none @endif">
                                    <div class="accordion-header" id="headingFaq{{ $item->cod_faq }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-faq{{ $item->cod_faq }}" aria-expanded="false"
                                            aria-controls="collapse-faq{{ $item->cod_faq }}">
                                            <p>{{ $item->titulo_faq }}</p>
                                        </button>
                                    </div>
                                    <div id="collapse-faq{{ $item->cod_faq }}" class="accordion-collapse collapse"
                                        aria-labelledby="headingFaq{{ $item->cod_faq }}"
                                        data-bs-parent="#accordion-{{ $cat->parent_faqcat }}">
                                        <div class="accordion-body">
                                            {!! $item->desc_faq !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
							@if(count($data['itemsCats'][$cat->cod_faqcat]) > 5)
                            <p class="text-end pt-3">
                                <a class="accordion-show"
                                    data-show="false" data-text-show="VER MÁS" data-text-hidden="OCULTAR">VER MÁS</a>
                            </p>
							@endif
                        </div>
                    @endif
                @endif
            @endforeach
        </div>

    </main>

@stop
