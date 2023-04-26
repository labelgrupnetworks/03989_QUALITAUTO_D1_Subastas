
<div class="col-xs-12 col-sm-6 mb-2 ">
    <a title="{{$lot->descweb_hces1}}" class="secondary-color-text" href="{{ \Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1)}}" >
        <div class="col-xs-12 no-padding lot-remates-destacados" >
            <div class="col-xs-12  no-padding">
                <div class="border_img_lot">
                    <div class="item_img">
                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                        <img class="img-responsive lazy" style="display: none" data-src="{{ Tools::url_img('lote_medium',$lot->num_hces1,$lot->lin_hces1) }}" alt="{{$lot->descweb_hces1}}">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 ">
                <div class=" ml-1 mr-1" >

					<div class="title_lot max-line-3">
						{{ trans(\Config::get('app.theme').'-app.lot.lot-name') }} {{ $lot->ref_asigl0}}: 	{{ $lot->descweb_hces1}}
					</div>
					<div class="desc_lot max-line-3">
						<?= $lot->desc_hces1 ?>
					</div>

					<div class="price_lot">
						{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}: {{\Tools::moneyFormat($lot->impsalhces_asigl0,"€",0)}}
						<br/>
						<span>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}: {{\Tools::moneyFormat($lot->implic_hces1,"€",0)}} </span>
					</div>
            	</div>
        	</div>
     </div>
    </a>
</div>
