<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3  square" >
	<a title="{{ $lot->descweb_hces1 }}" class=" link_artist" href="{{\Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1)}}" >

		<div class="lot_artist">
			<div class="item_img">

					<img class="img-responsive"  src="{{\Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1)}}" alt="{{$lot->descweb_hces1 }}">

			</div>

			<div class="data-container">
				<div class="title_item">
					{{$lot->descweb_hces1 }}
				</div>


			</div>

		</div>
	</a>
</div>
