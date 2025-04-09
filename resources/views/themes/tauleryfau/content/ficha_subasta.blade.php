@if (!empty($data["auction"]))
<?php

$file_code = $data['auction']->emp_sub . '_' . $data['auction']->cod_sub . '_' .$data['auction']->reference;
$user = (Session::get('user'));

?>
<section class="principal-bar no-principal">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="princiapl-bar-wrapper">
					<div class="principal-bar-title">
						<h3 class="titlePage">{{ $data["auction"]->des_sub}}</h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="auc-info">
	<div class="container">
		<div class="it_ro body-auctions" style="margin-bottom: 20px;">

			{{-- content-auction-desc --}}
			<div class="content-auction-desc-container">
				<div class="content-auction-desc">
					<div class="col-xs-9 no-padding direction-content">
						<div class=" valign">
							@if((!empty($data["auction"]->sesfechas_sub)) ||
							(!empty($data["auction"]->seshorario_sub)) ||
							(!empty($data["auction"]->seslocal_sub)) || (!empty($data["auction"]->sesmaps_sub)))
							<h5 style="color: #a37a4c; text-transform: uppercase;">
								<strong>{{ trans($theme . '-app.subastas.inf_subasta_subasta') }}</strong>
							</h5>
							@endif
							@if(!empty($data["auction"]->seslocal_sub))
							<strong><dfn>(<?= $data["auction"]->seslocal_sub ?>)</dfn></strong>
							@endif
						</div>
						<p>
							@if(!empty($data["auction"]->sesfechas_sub))
							<strong><?= $data["auction"]->sesfechas_sub ?></strong>
							@endif
							@if(!empty($data["auction"]->seshorario_sub))
							<?= $data["auction"]->seshorario_sub ?>
							@endif
						</p>
					</div>
					@if(!empty($data["auction"]->sesmaps_sub))
					<div class="col-xs-12 col-md-3 google-howGet no-padding">
						<a class="" target="_blank"
							title="{{ trans($theme . '-app.subastas.how_to_get') }}"
							href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>">
							{{ trans($theme . '-app.subastas.how_to_get') }}
						</a>
					</div>
					@endif
				</div>
				<div class="content-auction-desc">
					<div class="col-md-9 col-xs-12 no-padding direction-content">
						<div class=" valign">
							@if( (!empty($data["auction"]->expofechas_sub)) ||
							(!empty($data["auction"]->expohorario_sub)) ||
							(!empty($data["auction"]->expolocal_sub)) ||
							(!empty($data["auction"]->expomaps_sub)))
							<h5 style="">
								<strong>{{ trans($theme . '-app.subastas.inf_subasta_exposicion') }}</strong>
							</h5>
							@endif
							@if(!empty($data["auction"]->expolocal_sub))
							<strong><dfn>(<?= $data["auction"]->expolocal_sub ?>)</dfn></strong>
							@endif
						</div>
						<p>
							@if(!empty($data["auction"]->expofechas_sub))
							<strong><?= $data["auction"]->expofechas_sub ?></strong>
							@endif
							@if(!empty($data["auction"]->expohorario_sub))
							<?= $data["auction"]->expohorario_sub ?>
							@endif
						</p>
					</div>
					@if(!empty($data["auction"]->expomaps_sub))
					<div class="col-xs-12 col-md-3 google-howGet no-padding">
						<a class="" target="_blank"
							title="{{ trans($theme . '-app.subastas.how_to_get') }}"
							href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>">
							{{ trans($theme . '-app.subastas.how_to_get') }}
						</a>
					</div>
					@endif
				</div>
			</div>

			{{-- btn-list-auc --}}
			@foreach ($data['sessions'] as $session)
			<?php
					//ver si la subasta está cerrada
                    $SubastaTR = new \App\Models\SubastaTiempoReal();
					$SubastaTR->cod = $session->auction;
                    $SubastaTR->session_reference =  $session->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
                    $status  = $SubastaTR->getStatus();
                    $subasta_finalizada = false;

					if(!empty($status) && $status[0]->estado == "ended" ){
                        $subasta_finalizada = true;
					}
                ?>

			<div class="btn-list-auc flex">

				@if( $data['auction']->tipo_sub == 'W')
				<div class="btn-view-lot-new" style=" width: <?= ($subasta_finalizada)? '100%' : '48%'; ?>;">
					<a class="" style=""
						{{-- href="{{Routing::translateSeo('subasta').$session->auction."-".str_slug($session->name)."-".$session->id_auc_sessions }}" --}}
						href="{{\Tools::url_auction($data['auction']->cod_sub,$data['auction']->name,$session->id_auc_sessions,$session->reference) }}"
						title="{{ trans($theme . '-app.subastas.see_lotes') }}">{{ trans($theme . '-app.subastas.see_lotes') }}</a>
				</div>
				@endif

				<?php
						$url_tiempo_real=\Routing::translateSeo('api/subasta').$data['auction']->cod_sub."-".str_slug($session->name)."-".$session->id_auc_sessions;
					?>

				@if( $data['auction']->tipo_sub =='W' && $subasta_finalizada == false)
				<div class="btn-view-lot-new-online" style=" width: 48%">
					<a style="" href="{{ $url_tiempo_real }}"
						target="_blank">{{ trans($theme . '-app.lot.bid_live') }}</a>
				</div>
				@endif
			</div>

			@endforeach

			{{-- auc-observation --}}
			@if(!empty($data['auction']->descdet_sub))
			<div class="auc-observation">
				<div class="auc-observation-content-textarea">
					<textarea style="
                            color:#283747;
                            max-width: 100%;
                            min-width: 100%;
                            max-height: 100%;
                            min-height: 100%;" disabled>
                            {{ $data['auction']->descdet_sub }}
                        </textarea>
				</div>
			</div>
			@endif

			{{-- info-btns --}}
			<div class="no-padding info-btns">
				<ul class="single-auction-btn">
					<li class="">
						@if($data['auction']->tipo_sub != 'V')
						<a class="d-flex justify-content-center align-items-center" style="height:52px" target="_blank"
							href="<?php echo Routing::translateSeo('pagina').trans($theme . '-app.links.term_condition_sub')?>"><span>{{ trans($theme . '-app.foot.term_condition_sub') }}</span></a>
						<br>
						<a class="d-flex justify-content-center align-items-center hidden" style="height:52px;cursor:pointer;"
							href="/files/politica de envíos-2021.pdf"
							target="_blank">{{ trans($theme . '-app.foot.shipping_terms') }}</a>
						@endif
					</li>

					@if ($session->pedircatalogo=='S')
					<li class="">
						@if(!Session::has('user'))
						<a data-toggle="modal" data-target="#modalLogin"
							role="button"><span>{{ trans($theme . '-app.subastas.pdf_adj') }}</span></a>
						@else
						<a style="height:32px" data-subasta="{{$data['auction']->cod_sub}}" id="catalogue"
							data-lang="{{Config::get('app.locale')}}"
							role="button"><span>{{ trans($theme . '-app.subastas.pdf_adj') }}</span><i
								class="fas fa-check-circle"></i></a>
						@endif
					</li>
					@endif

					@php($lang = App::getLocale())

					@if( ($session->upcatalogo == 'S'))
					<li class="col-md-12 col-xs-6"><a target="_blank" class="cat-pdf"
							href="{{Tools::url_pdf($session->auction,$session->reference,'cat')}}"
							role="button">{{ trans($theme . '-app.subastas.pdf_catalog') }}</a>
					</li>
					@endif
					@if( $session->upmanualuso == 'S')
					<li class="col-md-12 col-xs-6"><a target="_blank"
							href="{{Tools::url_pdf($session->auction,$session->reference,'man')}}"
							role="button">{{ trans($theme . '-app.subastas.manual') }}</a></li>
					@endif
					@if( $session->uppreciorealizado == 'S')
					<li class="col-md-12 col-xs-6"><a target="_blank"
							href="{{Tools::url_pdf($session->auction,$session->reference,'pre')}}" class="price-done"
							role="button">{{ trans($theme . '-app.subastas.price_auction') }}</a>
					</li>
					@endif

				</ul>
			</div>

			{{-- img-info-subasta --}}
			<div class="img-info-subasta">
				<div class="img-subasta">
					<div class="img-border-auction">
						<a
							href="{{\Tools::url_auction($data['auction']->cod_sub,$data['auction']->name,$session->id_auc_sessions,$session->reference) }}">

							<img src="{{ Tools::url_img_auction('subasta_large', $data["auction"]->cod_sub) }}"
								class="img-responsive" style="margin:0 auto">

						</a>
					</div>
				</div>
			</div>

			{{-- shared --}}
			<div class="shared hidden-xs">
				<div class="text-center" style="margin-top: 10px;font-weight: 900;">
					{{ trans($theme . '-app.subastas.shared_auctions') }}</div>
				<ul class="red inline-flex valign">
					<li class="valign flex">
						<a title="Compartir por e-mail" target="_blank"
							href="mailto:?Subject={{ trans($theme . '-app.head.title_app') }}&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
							<i class="fa fa-envelope"></i>
						</a>
					</li>
					<li class="valign flex">
						<a title="Compartir en Twitter" target="_blank"
							href="http://twitter.com/share?url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&amp;text=<?= $data["auction"]->des_sub?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
							<i class="fab fa-twitter"></i>
						</a>
					</li>
					<li class="valign flex">
						<a title="Compartir en Facebook" target="_blank"
							href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
							<i class="fab fa-facebook"></i>
						</a>
					</li>
					<li class="valign flex">
						<a title="Compartir en pinterest" target="_blank"
							href="http://pinterest.com/pin/create/button/?media=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&amp;text=<?= $data["auction"]->des_sub?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
							<i class="fab fa-pinterest-p"></i>
						</a>
					</li>
				</ul>
			</div>

		</div>
	</div>
	@else
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-4">
				<div class="single_auction">
					<h1 class="titleSingle">{{trans($theme . '-app.lot.auction_not_found')}}</h1>
				</div>
			</div>
		</div>
	</div>
	@endif
</section>
@if ($data['auction']->tipo_sub == 'W')
<script type="application/ld+json">
	{
      "@context": "https://schema.org/",
      "@type": "SaleEvent",
      "name": "{!! str_replace('"',"'",$data['auction']->name) !!}",
      "image": "/img/load/subasta_large/AUCTION_{{ $data["auction"]->emp_sub }}_{{ $data["auction"]->cod_sub }}.jpg",
      "description": "<?= strip_tags( !empty($data['auction']->descdet_sub)? $data['auction']->descdet_sub : $data['auction']->description) ?>",
      "startDate": "<?= $data['auction']->start ?>",
      "endDate": "<?= $data['auction']->end ?>",
      "location":{
           "@type": "Place",
           "address": "<?= !empty($data['auction']->expolocal_sub)? $data['auction']->expolocal_sub : '' ?>"
        }
    }
</script>
@endif
