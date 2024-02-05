@php
$category = App\Models\V5\FgOrtsec1::select('lin_ortsec1', 'des_ortsec0', 'key_ortsec0')->JoinFgOrtsec0()->where('sec_ortsec1', $lote_actual->sec_hces1)->first();
$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
@endphp

<div class="description" style="--max-lines: 3; --line-height: 1.5">
	<p>{!! str_replace('&nbsp;', ' ', $lote_actual->desc_hces1) !!}</p>
</div>

<button data-js="show-more" data-txt-showmore="Ver más" data-txt-showless="Ver menos" class="btn btn-outline-lb-primary active d-none">
	Ver menos
</button>

@if(count($caracteristicas) !== 0)
	<div class="features">
		<h5>{{ trans("$theme-app.features.features") }}</h5>

		<div class="gird-features">
			@foreach($caracteristicas as $caracteristica)
				<p class="feature-name">
					{{ trans("$theme-app.features.$caracteristica->name_caracteristicas") }}
				</p>
				<p class="feature-value">{{$caracteristica->value_caracteristicas_hces1}}</p>
			@endforeach
		</div>

	</div>
@endif

@if(!empty($category))
<div class="categories">
	<h5>{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</h5>

	<a class="no-decoration" href="{{ route("category", ["keycategory" => $category->key_ortsec0]) }}" alt="{{$category->des_ortsec0}}">
		<span class="badge badge-custom-primary">{{$category->des_ortsec0}}</span>
	</a>
</div>
@endif

<script>

	if(isDescriptionOverflow()) {
		$('[data-js="show-more"]').removeClass('d-none');
	}

	$('[data-js="show-more"]').on('click', function() {
		$(this).toggleClass('active');

		$(this).text($(this).hasClass('active') ? $(this).data('txt-showless') : $(this).data('txt-showmore'));
		$('.description').toggleClass('max-lines');
	});

	function isDescriptionOverflow() {
		const description = document.querySelector('.description');

		// esta condición es para que se muestre el botón de ver más si el texto es muy largo
		// pero la última petición es que por defecto se muestre el texto completo
		//return description.scrollHeight > description.clientHeight;
		const coputedStyle = window.getComputedStyle(description);

		const maxLines = parseInt(coputedStyle.getPropertyValue('--max-lines'));
		const lineHeight = parseFloat(coputedStyle.getPropertyValue('--line-height'));
		const fontSize = parseFloat(coputedStyle.getPropertyValue('font-size'));

		return description.clientHeight > (maxLines * lineHeight * fontSize);
	}

</script>
