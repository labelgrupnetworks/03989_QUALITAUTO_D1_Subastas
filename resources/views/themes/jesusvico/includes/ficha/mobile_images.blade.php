@section('assets_components')
<link rel="stylesheet" href="/vendor/smartPhoto/smartphoto.min.css">
<script defer src="/vendor/smartPhoto/jquery-smartphoto.min.js"></script>
<script defer>
	$(function(){
		$(".js-smartPhoto").SmartPhoto();
	});
</script>
@endsection



@foreach($lote_actual->imagenes as $key => $imagen)
<div class="item_content_img_single">
	<a href="{{Tools::url_img('lote_large',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}"
		class="js-smartPhoto" data-caption="{{$lote_actual->titulo_hces1}}" data-id="id-{{$key + 1}}">
		<img class="lazy" src="{{Tools::url_img('lote_medium',$lote_actual->num_hces1,$lote_actual->lin_hces1, $key)}}" data-group="nogroup">
	</a>
</div>
@endforeach
