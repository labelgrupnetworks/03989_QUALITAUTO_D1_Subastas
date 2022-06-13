<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 square" >
	<a title="{{ $item->descweb_hces1 }}" class=" link_artist" href="{{\Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->descweb_hces1)}}" >

		<div class="lot_artist">
			<div class="item_img">
				<img class="img-responsive"  src="{{\Tools::url_img('lote_medium', $item->num_hces1, $item->lin_hces1)}}" alt="{{$item->descweb_hces1 }}">
			</div>

			<div class="data-container">
				@php
					$cerrado = $item->cerrado_asigl0 == 'S'? true : false;
					$oferta = $item->oferta_asigl0 == 2? true : false;
					$descuento = "";
					$retirado = $item->retirado_asigl0 !='N'? true : false;
					$devuelto = ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R' || $item->cerrado_asigl0 == 'D') ? true : false;
					$awarded	 = \Config::get('app.awarded');
					$cerrado = $item->cerrado_asigl0 == 'S'? true : false;
					$precio_venta = \Tools::moneyFormat($item->implic_hces1);
					$sub_historica = $item->subc_sub == 'H'? true : false;

				@endphp
				@include('includes.grid.labelLots')
				<div class="title_item">

					{{$item->descweb_hces1 }}
				</div>

			</div>
		</div>
	</a>
</div>
