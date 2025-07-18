@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@php
    $keyPage = $data['data']->key_web_page;
    $id = $data['data']->id_web_page;

    $isHowBuyOrSellPage = in_array($keyPage, ['comprar', 'vender']);
	$isAboutUsPage = in_array($keyPage, ['quienes-somos']);
@endphp

@section('content')

    <main class="static-page">

        <section class="fluid-banner">
            {!! BannerLib::bannersPorKey(
                $keyPage,
                '',
                ['dots' => false, 'autoplay' => true, 'autoplaySpeed' => 5000, 'slidesToScroll' => 1, 'arrows' => false],
                null,
                false,
                ''
            ) !!}
        </section>

        <section class="contenido-web" id="pagina-{{ $id }}">
            {!! $data['data']->content_web_page !!}
        </section>


        @if ($isHowBuyOrSellPage)
            <section class="container py-3">
                <div class="row">
                    <div class="col-lg-9">
                        <x-howbuy-block headText="{{ trans("$theme-app.pages.how_buy_sell_text_2") }}"
                            highlightedText="{{ trans("$theme-app.pages.how_buy_sell_title_2") }}" linkText="{{ trans("$theme-app.pages.how_buy_sell_link_text_2") }}"
                            linkAction="{{ route('valoracion', ['key' => 'articulos', 'lang' => config('app.locale')]) }}" />
                    </div>
                </div>
            </section>
        @endif

		@if($isAboutUsPage)
			@include('includes.team')
		@endif

    </main>

@stop
