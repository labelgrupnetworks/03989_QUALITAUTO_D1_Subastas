@if (!empty($data["auction"]))

<section class="header-info-auction">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="img-info-auction" style="background-image: url(/img/pruebas_subasta.png);    background-image: url(/img/load/subasta_large/AUCTION_{{ $data["auction"]->emp_sub }}_{{ $data["auction"]->cod_sub }}.jpg);background-size: cover;background-repeat: no-repeat;background-position: center;margin-top: 10px;"></div>

            </div>
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="title-info-auction">{{ $data["auction"]->des_sub}}</h1>
            <div class="links-aution">
                            @foreach ($data['sessions'] as $session)
                            <div class="link-auction">
                                <small>{{$session->name}}</small><a class="btn btn-view-lot" title="Ver lotes" href="<?= Routing::translateSeo('subasta').$session->auction."-".str_slug($session->name."-".$session->id_auc_sessions) ?>">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                            </div>
                            @endforeach
                        </div>
        </div>
        <div class="col-xs-12">
            <div class="info-titles-sub"><strong>{{ trans(\Config::get('app.theme').'-app.lot.description-title') }}</strong></div>
            <p><?= empty($data["auction"]->descdet_sub)? ' ' : $data["auction"]->descdet_sub; ?></p>
        </div>
        <div class="col-xs-12">
            @if(!empty($data["auction"]->expofechas_sub))
             <div class="info-titles-sub"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.fechas') }}</strong></div>
                <p><?= $data["auction"]->expofechas_sub ?></p>
                @endif
                @if(!empty($data["auction"]->expohorario_sub))
                 <div class="info-titles-sub"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.horarios') }}</strong></div>
                    <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->expohorario_sub ?> </p>
                @endif
                @if(!empty($data["auction"]->expolocal_sub))
                 <div class="info-titles-sub"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.localizacion') }}</strong></div>
                    <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->expolocal_sub ?></p>
                @endif
                @if(!empty($data["auction"]->expomaps_sub))
                    <p><a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
                @endif


        </div>

		<div class="col-xs-12 share-panel-auction">
				@include('includes.ficha.share_ficha_subasta')
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
