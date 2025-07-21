@php
	use App\Models\Enterprise;
	use App\Services\Auction\AuctionService;

	$auctionsW = [];
	if($global['auctionTypes']->where('tipo_sub', 'W')->value('count')) {
		$auctionsW = (new AuctionService)->getActiveAuctionsToType('W');
	}
 @endphp

<footer>
	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-lg-12 pr-3 pl-3">

				<div class="row">

					<div class="col-xs-12 col-md-12 col-lg-4 text-center mt-3 mb-3">

							<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">

							<ul  class="ul-format list-lang d-inline-flex redes-sociales d-flex justify-content-center">
								<li class="facebook">
									<a href="https://www.facebook.com/duran.subastas" target="_blank">
										<i class="fab fa-facebook-f"></i>
									</a>
								</li>
								<li class="instagram">
									<a href="https://instagram.com/duransubastas/" target="_blank">
										<i class="fab fa-instagram"></i>
									</a>
								</li>
								<li class="twitter">
									<a href="https://twitter.com/duransubastas" target="_blank">
										@include('components.x-icon', ['size' => '15'])
									</a>
								</li>
								<li class="youtube">
									<a href="https://www.youtube.com/channel/UCKWEKBgBba5RGYaDRiSdHDA/videos" target="_blank">
										<i class="fab fa-youtube"></i>
									</a>
								</li>
								<li class="email">
									<a href="{{ Routing::translateSeo(trans($theme.'-app.links.contact')) }}" >
										<i class="fas fa-envelope"></i>
									</a>
								</li>
							</ul>

					</div>

					<div class="col-xs-12 col-md-6 col-lg-4 text-left mt-3">

						<div class="row">

							<div class="col-xs-12">
								<div class="footer-title">
									{{ trans($theme.'-app.foot.enlaces_interes') }}
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<ul class="ul-format footer-ul">

									@foreach($auctionsW as $auction)
										<li>
											<a class="footer-link" href="{{ \Tools::url_auction($auction->cod_sub, $auction->des_sub, null, '001') }}">
												{{mb_strtoupper ($auction->des_sub) }}
											</a>
										</li>
									@endforeach

								   	<li><a class="footer-link"  href="{{ trans("$theme-app.links.footer_online_auction") }}">{{ mb_strtoupper (trans($theme.'-app.foot.online_auction')) }} </a></li>

									<li><a  class="footer-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ mb_strtoupper (trans($theme.'-app.metas.title_historic'))  }}</a></li>
									<li><a class="footer-link" href="{{ trans("$theme-app.links.footer_compra_ahora") }}">{{  mb_strtoupper (trans($theme.'-app.foot.compra_ahora')) }}</a></li>
									<li><a class="footer-link" href="{{ trans("$theme-app.links.footer_ventas_privadas") }}">{{   mb_strtoupper ( trans($theme.'-app.foot.ventas_privadas')  ) }}</a></li>

									@php
										#TODO:	Cambiar las URL hardcoded por dinámicas.
										#		Esto se podrá hacer posible cuando en la tabla FXSUBSEC_LANG estén las versiones en inglés respectivas.
										# Lluc - 06/06/2024
									@endphp
									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-rolex">{{ mb_strtoupper ("subasta ROLEX"  ) }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-patek-philippe">{{ mb_strtoupper ("subasta Patek Philippe") }}</a>
									</li>


									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-tag-hauer">{{ mb_strtoupper ("subasta Tag Hauer") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-omega">{{ mb_strtoupper ("subasta Omega") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-cartier">{{ mb_strtoupper ("subasta Cartier") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-longines">{{ mb_strtoupper ("subasta Longines") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-audermars-piguet">{{ mb_strtoupper ("Audermars Piguet") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-van-cleef-&-arpels">{{ mb_strtoupper ("subasta Van Cleef & Arpels") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-hermes">{{ mb_strtoupper ("subasta Hermes") }}</a>
									</li>

									<li>
										<a class="footer-link" href="/es/duran-subastas-subasta-louis-vuitton">{{ mb_strtoupper ("subasta Louis Vuitton") }}</a>
									</li>

									</ul>
							</div>
							<div class="col-xs-12 col-md-6">
								<ul class="ul-format footer-ul">

									<li><a class="footer-link"  href="{{ \Routing::translateSeo('calendar') }}">{{ mb_strtoupper (trans($theme.'-app.foot.calendar')) }}</a></li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_buy')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.how_to_buy')  ) }}</a>
									</li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.how_to_sell')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.how_to_sell')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.valorar_producto')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.tasaciones')  ) }}</a>
									</li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.informacion-general')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.informacion-general')  ) }}</a>
									</li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.informacion-comprador')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.informacion-comprador')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.informacion-vendedor')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.informacion-vendedor')  ) }}</a>
									</li>

									<li>
												<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.cambios-devoluciones')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.cambios_devoluciones')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.privacy')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.cookies')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.aviso_legal')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.aviso_legal')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.envios')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.envios')  ) }}</a>
									</li>
									<li>
										<button class="footer-link footer-link-button" type="button" data-toggle="modal" data-target="#cookiesPersonalize">
											{{ trans("$theme-app.cookies.configure") }}
										</button>
									</li>
									<li>
										<a class="footer-link" href="https://grupoduran-canaletico.appcore.es/" target="_blank">{{ mb_strtoupper ( trans($theme.'-app.foot.ethical_channel')  ) }}</a>
									</li>





									</ul>
							</div>

						</div>

					</div>

					<div class="col-xs-12 col-md-6 col-lg-4 text-left mt-3">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="footer-title">
									{{trans($theme.'-app.lot.categories')}}
								</div>
								<ul class="ul-format footer-ul">


									@php
										$fgortsec0 = new App\Models\V5\FgOrtsec0();
										$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();
									@endphp
									@foreach ($categories as $k => $category)
										<li><a class="footer-link" title="{!! $category["des_ortsec0"] !!}" href='{{ route("category",array("keycategory" => $category["key_ortsec0"])) }}' > {{$category["des_ortsec0"]}}</a></li>
									@endforeach
								</ul>
							</div>
							<div class="col-xs-12 col-md-6 mb-3">
								<div class="footer-title">
									{{ trans($theme.'-app.foot.duran_subastas') }}
								</div>
								<ul class="ul-format footer-ul">
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.about_us')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.about_us')  ) }}</a>
									</li>
									<?php /*
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.buzon-sugerencias')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.buzon_sugerencias')  ) }}</a>
									</li>
									*/
									?>
									<li>
											<a class="footer-link"href="/{{\Config::get('app.locale')}}/{{ trans($theme.'-app.links.faq')  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.faq')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo(trans($theme.'-app.links.contact'))  }}">{{ mb_strtoupper ( trans($theme.'-app.foot.contact')  ) }}</a>
									</li>

								</ul>
							</div>

						</div>
					</div>

				</div>


			</div>
		</div>
	</div>
</footer>

<div class="copy color-letter text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
			<p>Durán Sala de Arte {{ date("Y")}}</p>
				<p>
				<a style="text-transform: uppercase" class="color-letter" role="button" title="{{ trans($theme.'-app.foot.developedSoftware') }}" href="{{ trans($theme.'-app.foot.developed_url') }}" target="no_blank">{{ trans($theme.'-app.foot.developedBy')   }}</a>
				</p>
			</div>
		</div>
	</div>
</div>

<?php
#  \Tools::querylog();
?>

@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
