@php
$category = App\Models\V5\FgOrtsec1::select('lin_ortsec1', 'des_ortsec0', 'key_ortsec0')->JoinFgOrtsec0()->where('sec_ortsec1', $lote_actual->sec_hces1)->first();
$caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($lote_actual->num_hces1, $lote_actual->lin_hces1);
@endphp



<div class="description max-lines" style="--max-lines: 0; --line-height: 1.5">
	<p>{!! str_replace('&nbsp;', ' ', $lote_actual->desc_hces1) !!}</p>
</div>

@if(count($caracteristicas) !== 0)
	<div class="features mt-3">
		<h5>Caracteristicas</h5>

		<table class="w-100">
			<tbody>
				@foreach($caracteristicas as $caracteristica)
				<tr>
					<td>{{$caracteristica->name_caracteristicas}}</td>
					<td>{{$caracteristica->value_caracteristicas_hces1}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
@endif

@if(!empty($category))
<div class="categories mt-3">
	<h5>{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</h5>

	<a class="no-decoration" href="{{ route("category", ["keycategory" => $category->key_ortsec0]) }}" alt="{{$category->des_ortsec0}}">
		<span class="badge badge-custom-primary">{{$category->des_ortsec0}}</span>
	</a>
</div>
@endif
