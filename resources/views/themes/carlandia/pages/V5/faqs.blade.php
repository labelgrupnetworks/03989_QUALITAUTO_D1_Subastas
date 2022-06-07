@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')
<script>
	ga('send','event','VISITA SECCIONES','Visitas FAQs');
</script>
<div class="faq-banner-container">
	{!! BannerLib::bannersPorKey('preguntas-bansup', 'faq-banner', ['dots' => false, 'arrows' => false, 'autoplay' => true]) !!}
</div>

<div class="container faq-page-container">
	<div class="bread-faq">
		@include('includes.breadcrumb')
		<h1 class="h1-seo titlePage title-color mb-2 mt-2" style="font-size:25px">Te resolvemos las <strong>preguntas más frecuentes</strong></h1>
	</div>
</div>

<div class="container faq-page-container">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 color-letter">

			<div class="faq-page">

				<div id="faq" class="pb-5">

					<div class="row">

						@foreach (collect($data['cats'])->where('cod_faqcat', request('faq', '2')) as $cat)

						@if (!empty($data['itemsCats'][$cat->cod_faqcat]))
						@foreach ($data['itemsCats'][$cat->cod_faqcat] as $item)

						<div class="col-xs-12 mb-3 parentFaq parentFaq{{ $item->cod_faqcat }}">

							<p><strong>
									<a class="question" role="button" aria-expanded="false"
										aria-controls="collapse_{{ $item->cod_faq }}" data-toggle="collapse"
										data-target="#collapse_{{ $item->cod_faq }}">
										{{-- <span>+</span> --}}
										{!! $item->titulo_faq !!}
							</a>
								</strong>
							</p>

							<div class="collapse response faq-desc-color-black" id="collapse_{{ $item->cod_faq }}">
								{!! $item->desc_faq !!}
								{{-- {!! str_replace(['.', ':'], ['.<br>', ':<br>'], $item->desc_faq) !!} --}}
							</div>

						</div>

						@endforeach
						@endif

						@endforeach

					</div>

					<div>
						<a class="button-principal" href="{{ route('allCategories') }}">{{ trans("$theme-app.home.buscar") }}</a>
					</div>

				</div>

			</div>

		</div>
	</div>
</div>
<div class="down-static-banner mb-5">
	{!! BannerLib::bannersPorKey('preguntas-baninf', 'faq-banner', ['dots' => false, 'arrows' => false, 'autoplay' => true]) !!}
</div>

@stop
