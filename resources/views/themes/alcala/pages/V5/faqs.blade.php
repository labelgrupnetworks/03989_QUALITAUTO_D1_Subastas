@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.foot.faq') }}
@stop

@section('content')
<?php
    $bread[] = [
        "name" => "name",
        "url" => URL::current(),
        "title" => "title",
    ];
?>


    <div class="container" id="faq">

        <div class="featured-auctions-title">
            <p class="theme-subalia secondary-color">
                {{trans($theme.'-app.foot.faq') }}
            </p>
        </div>

        <br>

        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 col-sm-4">

                    <h4 class="hidden-xs">{{trans($theme.'-app.faq.select_category') }}</h4>
                    <hr>
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

                    <h4 class="hidden-xs">{{trans($theme.'-app.faq.select_sub_category') }}</h4>

                    <div class="block">
                        @foreach ($data['cats'] as $item)

                            @if (!empty($item->parent_faqcat) && !$item->parent_faqcat == 0)
                                <a href="javascript:muestraFaq({{ $item->cod_faqcat }})" class="subcat subfamily parent{{ $item->parent_faqcat }}">
                                    {{ $item->nombre_faqcat }}
                                    <span>></span>
                                </a>
                            @endif

                        @endforeach
                    </div>

                </div>

                <div class="col-xs-12 col-sm-4">

                    <h4 class="hidden-xs">{{trans($theme.'-app.faq.select_question') }}</h4>

                    <div class="block">
                        @foreach ($data['items'] as $item)

                            <div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
                                <a href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')" class="question">
                                    <span>+</span>
                                    {{ $item->titulo_faq }}
                                </a>
                                <div id="faq{{ $item->cod_faq }}" class="faq" >
                                    {{ $item->desc_faq }}
                                    <br>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br><br><br><br><br><br><br><br><br>


@stop

