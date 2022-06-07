@php
	$url = url()->previous();
	$showLotListButton = in_array(Route::currentRouteName(), ['contact_page', 'faqs_page']) || !empty($data['data']->id_web_page) && $data['data']->key_web_page != "info-adjudicacion";
@endphp

<div class="bread-content">

	<a
		class="btn-return btn button-principal"
		title="{{ trans(\Config::get('app.theme').'-app.subastas.breadcrumb') }}"
		href="{{ $url }}">
		{{ trans("$theme-app.emails.back") }}
	</a>

	@if($showLotListButton)
	<a class="btn-return btn button-principal" href="{{ route('allCategories') }}">{{ trans("$theme-app.home.buscar") }}</a>
	@endif

</div>
