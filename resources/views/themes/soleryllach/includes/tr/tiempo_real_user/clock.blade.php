<div id="clock" class="clock-page">
	<div class="row center-tr">
		<div class="col-xs-12 col-lg-3">
			 <img  style="margin: 0 auto" class="img-responsive" src="{{Tools::url_img_session('real',$data['subasta_info']->cod_sub,$data['subasta_info']->reference)}}">
		</div>
		<div class="col-xs-12 col-lg-6 data-logo-wait-info data-content-info-wait">
			<img class="logo-time-wait img-responsive" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png?a=1"  alt="{{(\Config::get( 'app.name' ))}}">
			<p>{{trans(\Config::get('app.theme')."-app.msg_neutral.auction_coming_soon")}}</p>
			<div data-countdown="{{strtotime($tiempo) - getdate()[0] }}"  data-format="<span><span class='date'>%D</span><span class='text'>{{trans(\Config::get('app.theme')."-app.msg_neutral.days")}}</span></span><br><span><span class='date'>%H</span><span class='text'>{{trans(\Config::get('app.theme')."-app.msg_neutral.hours")}}</span></span><span><span class='date'>%M</span><span class='text'>{{trans(\Config::get('app.theme')."-app.msg_neutral.minutes")}}</span></span><span><span class='date'>%S<span class='text'>{{trans(\Config::get('app.theme')."-app.msg_neutral.seconds")}}</span></span>" data-txtend ="{{trans(\Config::get('app.theme')."-app.msg_neutral.auction_coming_soon_minuts")}}" class="tiempo"></div>
		</div>
		<div class="col-xs-12 col-lg-3 data-content-info-wait">

			<p class="start_auction_date">{{strftime('%d/%m/%Y',strtotime($data['subasta_info']->start))}}</p>
			<p class="start_auction_hora">{{strftime('%H:%M',strtotime($data['subasta_info']->start))}} horas</p>

			<p class="start_auction_inf">{{trans(\Config::get('app.theme')."-app.lot_list.lots")}}</p>
			<?php
				if(!empty($data['subasta_info']) && !empty($data['subasta_info']->lote_actual)){
					$firstItem = $data['subasta_info']->lote_actual->ref_asigl0;
				}else{
					 $firstItem =  $data['subasta_info']->first_item;
				}


			?>
			<p class="start_auction_inf">{{$firstItem}} - <?= ($data['subasta_info']->last_item == '9999')?trans(\Config::get('app.theme')."-app.sheet_tr.finish_auction"): $data['subasta_info']->last_item  ?></p>


		</div>
	</div>
</div>
