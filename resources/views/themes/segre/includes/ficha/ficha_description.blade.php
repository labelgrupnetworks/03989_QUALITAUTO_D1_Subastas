@php
$category = App\Models\V5\FgOrtsec1::select('lin_ortsec1', 'des_ortsec0', 'key_ortsec0')->JoinFgOrtsec0()->where('sec_ortsec1', $lote_actual->sec_hces1)->first();
$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
@endphp



<div class="description">
	<p>{!! str_replace('&nbsp;', ' ', $lote_actual->desc_hces1) !!}</p>
</div>

@if(count($caracteristicas) !== 0)
	<div class="features mt-3">
		<h5>{{ trans("$theme-app.features.features") }}</h5>

		<div class="gird-features">
			@foreach($caracteristicas as $caracteristica)
				<p class="feature-name">{{$caracteristica->name_caracteristicas}}</p>
				<p class="feature-value">{{$caracteristica->value_caracteristicas_hces1}}</p>
			@endforeach
		</div>

	</div>
@endif

@if(!empty($category))
<div class="categories mt-3 d-none">
	<h5>{{ trans($theme.'-app.lot.categories') }}</h5>

	<a class="no-decoration" href="{{ route("category", ["keycategory" => $category->key_ortsec0]) }}" alt="{{$category->des_ortsec0}}">
		<span class="badge badge-custom-primary">{{$category->des_ortsec0}}</span>
	</a>
</div>
@endif
