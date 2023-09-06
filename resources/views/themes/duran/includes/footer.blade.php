
<?php
	$empre= new \App\Models\Enterprise;
	$empresa = $empre->getEmpre();
 ?>

<footer>
	<div class="container">

		<div class="row">
			<div class="col-xs-12 col-lg-12 pr-3 pl-3">

				<div class="row">

					<div class="col-xs-12 col-md-12 col-lg-4 text-center mt-3 mb-3">

							<img class="logo-company" src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"  alt="{{(\Config::get( 'app.name' ))}}" width="90%">

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
									<a href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.contact')  }}" >
										<i class="fas fa-envelope"></i>
									</a>
								</li>
							</ul>

					</div>

					<div class="col-xs-12 col-md-6 col-lg-4 text-left mt-3">

						<div class="row">

							<div class="col-xs-12">
								<div class="footer-title">
									{{ trans(\Config::get('app.theme').'-app.foot.enlaces_interes') }}
								</div>
							</div>

							<div class="col-xs-12 col-md-6">
								<ul class="ul-format footer-ul">
									@if($global['subastas']->has('S') && $global['subastas']['S']->has('W'))
										@foreach($global['subastas']['S']['W'] as $auction)
											@foreach($auction as $session)
												@if($session->reference == '001')
													<li>
														<a class="footer-link" href="{{ \Tools::url_auction($session->cod_sub,$session->des_sub,$session->id_auc_sessions, $session->reference) }}">{{mb_strtoupper ($session->des_sub) }}</a>
													</li>
												@endif
											@endforeach
										@endforeach
								   @endif

								   	<li><a class="footer-link"  href="/en/subasta/subasta-solo-online-duran_7501-001">{{ mb_strtoupper (trans(\Config::get('app.theme').'-app.foot.online_auction')) }} </a></li>

									<li><a  class="footer-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ mb_strtoupper (trans(\Config::get('app.theme').'-app.metas.title_historic'))  }}</a></li>
									<li><a class="footer-link" href="/en/subasta/tienda-online_7500-001?order=orden_desc">{{  mb_strtoupper (trans(\Config::get('app.theme').'-app.foot.compra_ahora')) }}</a></li>
									<li><a class="footer-link" href="/en/info-subasta/7503-venta-privada">{{   mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.ventas_privadas')  ) }}</a></li>
									<li><a class="footer-link"  href="{{ \Routing::translateSeo('calendar') }}">{{ mb_strtoupper (trans($theme.'-app.foot.calendar')) }}</a></li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.how_to_buy')  ) }}</a>
									</li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_sell')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.how_to_sell')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.valorar_producto')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.tasaciones')  ) }}</a>
									</li>

									</ul>
							</div>
							<div class="col-xs-12 col-md-6">
								<ul class="ul-format footer-ul">
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.informacion-general')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.informacion-general')  ) }}</a>
									</li>
									<li>
											<a class="footer-link" href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.informacion-comprador')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.informacion-comprador')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.informacion-vendedor')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.informacion-vendedor')  ) }}</a>
									</li>

									<li>
												<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cambios-devoluciones')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.cambios_devoluciones')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.privacy')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.cookies')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.aviso_legal')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.aviso_legal')  ) }}</a>
									</li>
									<li>
										<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.envios')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.envios')  ) }}</a>
									</li>


									</ul>
							</div>

						</div>

					</div>

					<div class="col-xs-12 col-md-6 col-lg-4 text-left mt-3">
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<div class="footer-title">
									{{trans(\Config::get('app.theme').'-app.lot.categories')}}
								</div>
								<ul class="ul-format footer-ul">


									@php
										$fgortsec0 = new App\Models\V5\FgOrtsec0();
										$categories = $fgortsec0->GetAllFgOrtsec0()->get()->toarray();
									@endphp
									@foreach ($categories as $k => $category)
										<li><a class="footer-link" title="{!! $category["des_ortsec0"] !!}" href='{{ route("category",array( "category" => $category["key_ortsec0"])) }}' > {{$category["des_ortsec0"]}}</a></li>
									@endforeach
								</ul>
							</div>
							<div class="col-xs-12 col-md-6 mb-3">
								<div class="footer-title">
									{{ trans(\Config::get('app.theme').'-app.foot.duran_subastas') }}
								</div>
								<ul class="ul-format footer-ul">
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.about_us')  ) }}</a>
									</li>
									<?php /*
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.buzon-sugerencias')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.buzon_sugerencias')  ) }}</a>
									</li>
									*/
									?>
									<li>
											<a class="footer-link"href="/{{\Config::get('app.locale')}}/{{ trans(\Config::get('app.theme').'-app.links.faq')  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.faq')  ) }}</a>
									</li>
									<li>
											<a class="footer-link"href="{{ Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact'))  }}">{{ mb_strtoupper ( trans(\Config::get('app.theme').'-app.foot.contact')  ) }}</a>
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
				<a style="text-transform: uppercase" class="color-letter" role="button" title="{{ trans(\Config::get('app.theme').'-app.foot.developedSoftware') }}" href="{{ trans(\Config::get('app.theme').'-app.foot.developed_url') }}" target="no_blank">{{ trans(\Config::get('app.theme').'-app.foot.developedBy')   }}</a>
				</p>
			</div>
		</div>
	</div>
</div>

<?php
#  \Tools::querylog();
?>

@if (!Cookie::get("cookie_law"))
	@include("includes.cookie")
<script>cookie_law();</script>
@endif
