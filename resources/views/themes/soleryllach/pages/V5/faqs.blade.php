@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.foot.faq') }}
@stop

@section('content')
    <main class="faqs-page">

        <div class="container" id="faq">

            <div class="featured-auctions-title">
                <h1 class="titlePage">
                    {{ trans(\Config::get('app.theme') . '-app.foot.faq') }}
                </h1>
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-4">

                        <h4 class="hidden-xs">{{ trans(\Config::get('app.theme') . '-app.faq.select_category') }}</h4>
                        <hr class="hidden-xs">
                        <div class="block">
                            @foreach ($data['cats'] as $item)
                                @if (empty($item->parent_faqcat) || $item->parent_faqcat == 0)
                                    <a href="javascript:muestraSub({{ $item->cod_faqcat }})" class="cat">
                                        {{ $item->nombre_faqcat }}
                                        <span>></span>
                                    </a>
                                @endif
                            @endforeach
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4">

                        <h4 class="hidden-xs">{{ trans(\Config::get('app.theme') . '-app.faq.select_sub_category') }}</h4>
                        <hr class="hidden-xs">
                        <div class="block">
                            @foreach ($data['cats'] as $item)
                                @if (!empty($item->parent_faqcat) && !$item->parent_faqcat == 0)
                                    <div class="subfamily parent{{ $item->parent_faqcat }}">
                                        <a href="javascript:muestraFaq({{ $item->cod_faqcat }})" class="subcat ">
                                            {{ $item->nombre_faqcat }}
                                            <span>></span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-4">

                        <h4 class="hidden-xs">{{ trans(\Config::get('app.theme') . '-app.faq.select_question') }}</h4>
                        <hr class="hidden-xs">
                        <div class="block">
                            @foreach ($data['items'] as $item)
                                <div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
                                    <a href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')" class="question">
                                        <span>+</span>
                                        {{ $item->titulo_faq }}
                                    </a>
                                    <div id="faq{{ $item->cod_faq }}" class="faq">
                                        {!! nl2br($item->desc_faq) !!}
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@stop
