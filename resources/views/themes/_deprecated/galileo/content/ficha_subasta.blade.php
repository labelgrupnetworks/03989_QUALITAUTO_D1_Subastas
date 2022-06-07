@if (!empty($data["auction"]))

<div class="info-auction color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-12 col-sm-7 info-auction-img">
                    <div class="info-auction-img-content">
                        <img  width="100%" src="/img/load/subasta_large/AUCTION_{{ $data["auction"]->emp_sub }}_{{ $data["auction"]->cod_sub }}.jpg" class="img-responsive">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5 info-auction-info">
                    <div class="info-auction-info-content">
                        <div class="info-auction-info-title">
                                <h1 class="">{{ $data["auction"]->des_sub}}</h1>
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
                                    <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }} <i class="fas fa-location-arrow"></i></a></p>
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
                                <p class="how_to_get_content"><a target="_blank" class="color-letter how_to_get" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>"><i class="fas fa-location-arrow"></i></a></p>
                            @endif

                               
                        </div>

                        <div class="block-auction exposition-des">
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
                                <p><a target="_blank" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
                            @endif


                        </div>

                        <div class="links-aution">
                            @foreach ($data['sessions'] as $session)
                            <div class="link-auction">
                                <p>{{$session->name}}</p>
                                <a class="button-principal-info-auc" title="Ver lotes" href="<?= Routing::translateSeo('subasta').$session->auction."-".str_slug($session->name."-".$session->id_auc_sessions) ?>">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                            </div>
                            @endforeach                           
                        </div>
                    </div>
                    <div class="zone-share-social col-xs-12 no-padding d-flex justify-content-space-between">
                        <p class="shared">{{ trans(\Config::get('app.theme').'-app.subastas.shared_auctions') }}</p>
                        <ul class="red ul-format d-flex align-items-center">
                            
                            <li class="btn-color"><a class=" d-flex align-items-center justify-content-center color-letter" href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
                                 <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            <li class="btn-color">
                                <a class=" d-flex align-items-center justify-content-center color-letter" title="Compartir por e-mail" href="http://twitter.com/share?url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&amp;text=<?= $data["auction"]->des_sub?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li class="btn-color">
                               <a class=" d-flex align-items-center justify-content-center color-letter" title="Compartir por e-mail" href="mailto:?Subject={{ trans(\Config::get('app.theme').'-app.head.title_app') }}&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fas fa-envelope-open"></i></a> 
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container hide">
	<div class="row it_ro">
            <div class="col-xs-12">
                <div class="col-sm-7 col-xs-12">
                    <div class="img-subasta">
                        <div class="img-border-auction">

                            <img  width="100%" src="<?= \Tools::url_img_auction('subasta_large',$data["auction"]->cod_sub)?>" class="img-responsive">
                           
                        </div>
                        
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="col-xs-12">
                         
                    </div>
                    <div class="col-xs-12 desc-panel-aution">

                        
                        <div class="content-auction-desc">
                        <div class="block-auction exposition-des">
                            
                        </div>

                        
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
