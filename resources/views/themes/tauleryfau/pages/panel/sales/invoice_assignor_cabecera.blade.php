@foreach ($subastas as $cod_sub => $factura)

@if(!empty($factura->linea))


<a data-toggle="collapse" href="#{{$cod_sub}}" data-parent="#auctions_accordion">
	<div class="panel-heading panel-heading-auction">
		<h4 class="panel-title">
			{{ $factura->linea->name }}
		</h4>
		<i class="fas fa-sort-down"></i>
	</div>
</a>

<div id="{{$cod_sub}}" class="panel-collapse collapse js-title-collapse" anum="{{$factura->anum_dvc0}}" num="{{$factura->num_dvc0}}">

	<div id="content_{{$cod_sub}}"></div>

</div>

@endif

@endforeach


<script>

$( document ).ready(function() {

	$('.js-title-collapse').on('show.bs.collapse', function (e) {
		let line = e.target;

		if($(`#content_${line.id}`).html() != ''){
			return;
		}


		let data = {
			'_token': '{{ csrf_token() }}',
			'anum': $(line).attr('anum'),
			'num': $(line).attr('num')
		}

		$.ajax({
			type: "POST",
			url: "{{ route('panel.salesFactura', ['lang' => Config::get('app.locale')]) }}",
			data: data,
			beforeSend: function(){

			},
			success: function(response){
				$(`#content_${line.id}`).html(response);
			}
		});

	});


});

</script>
