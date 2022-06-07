@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
@stop

@section('content')
<?php
    $bread[] = [
        "name" => trans(\Config::get('app.theme').'-app.foot.faq') ,
        "url" => URL::current(),
        "title" => "title",
    ];
?>
<div class="container">
	<div class="breadcrumb-total row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@include('includes.breadcrumb')
			<div class="container">
				<h1 class="titlePage">{{trans(\Config::get('app.theme').'-app.foot.faq') }}</h1>




					<div id="faq" class="col-xs-12 pb-5">
						<div class="row">
							@foreach ($data['cats'] as $cat)
								@if (!empty($cat->parent_faqcat) && !$cat->parent_faqcat == 0)
									<h4>  {{ $cat->nombre_faqcat }} </h4>

									@if (!empty($data['itemsCats'][$cat->cod_faqcat]))
										@foreach ($data['itemsCats'][$cat->cod_faqcat] as $item)
											<div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
												<strong>
													<a href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')" class="question">
														<span>+</span>
														<?= $item->titulo_faq ?>
													</a>
												</strong>
												<div id="faq{{ $item->cod_faq }}" class="faq" >
													<?= $item->desc_faq ?>
													<br>
												</div>
											</div>
										@endforeach
									@endif
								@endif
							@endforeach

						</div>
					</div>

			</div>
		</div>
	</div>
</div>








@stop

