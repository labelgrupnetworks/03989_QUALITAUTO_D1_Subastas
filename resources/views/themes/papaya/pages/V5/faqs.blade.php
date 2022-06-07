@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')
<?php
$bread[] = [
    "name" => "name",
    "url" => URL::current(),
    "title" => "title",
];
?>

<link href="/themes/papaya/css/page/faqs.css" rel="stylesheet" type="text/css"/>
<section class="all-aution-title title-content pb-1">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 h1-titl text-center">
                <h1 class="page-title mb-3">{{trans(\Config::get('app.theme').'-app.foot.faq') }}</h1>
            </div>
        </div>
    </div>
</section>


<div class="container" id="faq">


    <div class="col-xs-12 mt-5">
        <div class="row">
            <div class="col-xs-12 col-sm-4">

                <h4 class="hidden-xs faq-category">{{trans(\Config::get('app.theme').'-app.faq.select_category') }}</h4>

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

                <h4 class="hidden-xs faq-category">{{trans(\Config::get('app.theme').'-app.faq.select_sub_category') }}</h4>

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

                <h4 class="hidden-xs faq-category">{{trans(\Config::get('app.theme').'-app.faq.select_question') }}</h4>

                <div class="block">
                    @foreach ($data['items'] as $item)

                    <div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
                        <a href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')" class="question" id="questionfaq{{$item->cod_faq}}">
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

	<div class="col-xs-12 mt-5">
		<p>{!! trans(\Config::get('app.theme').'-app.global.contact') !!}</p>
	</div>
</div>

<br><br><br><br><br><br>

<script>

var idbuffer;

FaqshowContent = function(id){

    if (idbuffer != undefined){
        idbuffer.css('border-bottom', '1px solid #018ccc');
    }

    $(".faq").hide(500);
    $('#' + id).toggle('Drop');
    $('#question' + id).css('border-bottom', '1px solid #c09854');
    idbuffer = $('#question' + id);

};

</script>


@stop

