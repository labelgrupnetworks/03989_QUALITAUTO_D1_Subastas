@if (!empty($data["auction"]))

<div class="info-auction color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                <div class="col-xs-12 col-sm-5 info-auction-img no-padding">
                    <div class="info-auction-img-content">
                        <img 
                            data-src="{{\Tools::url_img_session('subasta_large',$data['auction']->cod_sub,$data['auction']->reference)}}" 
                            alt="{{ $data['auction']->name }}" 
                            class="img-responsive lazy"
                            style="display: none"                                
                            img-responsive">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-7 info-auction-info">
                    <div class="info-auction-info-content">
                        <div class="info-auction-info-title">
                               
                                <p><?= empty($data["auction"]->descdet_sub)? ' ' : $data["auction"]->descdet_sub; ?></p>
                        </div>
                        <div class="info-auction-info-address">
                                @if( (!empty($data["auction"]->expofechas_sub)) ||  (!empty($data["auction"]->expohorario_sub)) || (!empty($data["auction"]->expolocal_sub)) || (!empty($data["auction"]->expomaps_sub)))
                                <div class="info-auction-info-address-title">{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_exposicion') }}</div>
                                @endif
                                @if(!empty($data["auction"]->expofechas_sub))
                                    <p><?= $data["auction"]->expofechas_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->expohorario_sub))
                                    <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->expohorario_sub ?> </p>
                                @endif
                                @if(!empty($data["auction"]->expolocal_sub))
                                    <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->expolocal_sub ?></p>
                                @endif
                                @if(!empty($data["auction"]->expomaps_sub))
                                    <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="cómo llegar" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>"><i class="fas fa-map-marker-alt" style="font-size: 17px;"></i></a></p>
                                @endif
                                @if((!empty($data["auction"]->sesfechas_sub)) || (!empty($data["auction"]->seshorario_sub)) || (!empty($data["auction"]->seslocal_sub)) || (!empty($data["auction"]->sesmaps_sub)))
                            <div class="info-auction-info-address-title">{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_subasta') }}</div>
                            @endif
                            @if(!empty($data["auction"]->sesfechas_sub))
                               <p> <?= $data["auction"]->sesfechas_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->seshorario_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->seshorario_sub ?> </p>
                            @endif
                            @if(!empty($data["auction"]->seslocal_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->seslocal_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->sesmaps_sub))
                                <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>"><i class="fas fa-map-marker-alt" style="font-size: 17px;"></i></a></p>
                            @endif

                               
                        </div>
                    </div>
                    
                    <center>
                    @foreach ($data['sessions'] as $session)
                        <a title="{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}" style="text-decoration: none;" href="<?= Routing::translateSeo('subasta') . $session->auction . "-" . str_slug($session->name . "-" . $session->id_auc_sessions) ?>?order=ffin_desc&awardOpen=on">
                            <button class="lotlist-button-buy info-auction-button" id="p" style="width: 40%;">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</button>
                        </a>
                    @endforeach
                    </center>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>


@else
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<div class="single_auction">
				<h1 class="titleSingle">{{trans(\Config::get('app.theme').'-app.lot.auction_not_found')}}</h1>
                        </div>
                </div>
        </div>
</div>

@endif
