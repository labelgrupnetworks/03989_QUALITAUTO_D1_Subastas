@php
$empre= new \App\Models\Enterprise;
$empresa = $empre->getEmpre();
@endphp

{{-- container --}}
<footer>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-5 footer-module">

				<div class="row">


					<div class="col-xs-12 col-md-8">
						<div class="logo_footer">
							<img class="logo-company"
								src="/themes/{{\Config::get('app.theme')}}/assets/img/logo_footer.png"
								alt="{{(\Config::get( 'app.name' ))}}">
						</div>

						<div class="footer-address-title">
							<?= !empty($empresa->nom_emp)? $empresa->nom_emp : ''; ?> <br>
							<span
								class="footer-link footer-link-address no-hover"><?= !empty($empresa->dir_emp)? $empresa->dir_emp : ''; ?></span>
							<span
								class="footer-link footer-link-address no-hover"><?= !empty($empresa->cp_emp)? $empresa->cp_emp : ''; ?>
								<?= !empty($empresa->pob_emp)? $empresa->pob_emp : ''; ?> ,
								<?= !empty($empresa->pais_emp)? $empresa->pais_emp : ''; ?></span>
							<ul class="ul-format footer-ul">
								<li>
									<a class="footer-link footer-link-address"
										title="<?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?>"
										href="tel:{{$empresa->tel1_emp}}">Tel:
										<?= !empty($empresa->tel1_emp)? $empresa->tel1_emp : ''; ?></a>
								</li>
								<li>
									<a class="footer-link footer-link-address"
										title="<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"
										href="mailto:<?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?>"><?= !empty($empresa->email_emp)? $empresa->email_emp : ''; ?></a>
								</li>
							</ul>

						</div>

						<div class="footer-address-title">
							<span>Delegación Andalucia</span>
							<span class="footer-link footer-link-address no-hover">
								Marbella, Málaga
							</span>
							<span>
								<a class="footer-link footer-link-address" title="Teléfono"
									href="tel:+34608743835">Tel: +34 608 743 835</a>
							</span>
						</div>
					</div>

					<div class="col-xs-2 col-xs-offset-5 col-md-3 col-md-offset-0">
						<a href="/themes/{{$theme}}/assets/files/ISO 27001 CAST - INTERNATIONAL BUSINESS AUCTIONS_signed.pdf" target="_blank">
							<img class="img-responsive" src="/themes/{{$theme}}/assets/img/iso_27001.png" alt="ISO logo">
						</a>
					</div>
				</div>


			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 footer-module p-0 module-1">
				<div class="footer-title">
					{{ trans(\Config::get('app.theme').'-app.foot.auctions') }}
				</div>
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link"
							href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.ongoing_auctions')}}</a>
					</li>

					<li>
						<a class="footer-link"
							href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans(\Config::get('app.theme').'-app.foot.historico')}}</a>
					</li>

					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
							href="<?= \Routing::translateSeo('administradores-concursales') ?>"><span>{{trans(\Config::get('app.theme') . '-app.foot.bankruptcy_administrators')}}</span></a>
						<?php /*
                                        </li>
                                        <li><a class="footer-link" href="{{ \Routing::translateSeo('subastas-online') }}">{{ trans(\Config::get('app.theme').'-app.foot.online_auction')}}</a></li>
                                        <li><a class="footer-link" href="{{ \Routing::translateSeo('venta-directa') }}">{{ trans(\Config::get('app.theme').'-app.foot.direct_sale')}}</a></li>
                                                *
                                                */
                                        ?>
				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2 footer-module p-0 module-2">
				<div class="footer-title">
					{{ trans(\Config::get('app.theme').'-app.foot.enterprise') }}
				</div>
				<ul class="ul-format footer-ul">
					<li><a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.about_us')  ?>">{{ trans(\Config::get('app.theme').'-app.foot.about_us') }}</a>
					</li>

					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.contact')}}"
							href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.contact')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.contact2')}}</span></a>

					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.faq')}}"
							href="<?= \Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.faq')) ?>"><span>{{ trans(\Config::get('app.theme').'-app.foot.faq')}}</span></a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.psi')}}"
							href="/files/PSI_v2.pdf" target="_blank"><span>{{ trans(\Config::get('app.theme').'-app.foot.psi')}}</span></a>
					</li>

					<li>
						<a class="footer-link" title="{{ trans("$theme-app.foot.map") }}"
							href="{{\Routing::translateSeo(trans(\Config::get('app.theme').'-app.links.map')) }}"><span>{{ trans("$theme-app.foot.map") }}</span></a>
					</li>

				</ul>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-4 footer-module p-0">
				<div class="footer-title">
					{{ trans(\Config::get('app.theme').'-app.foot.term_condition')}}
				</div>
				<ul class="ul-format footer-ul">
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.term_condition') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.term_condition')?>">{{ trans(\Config::get('app.theme').'-app.foot.aviso_legal') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.privacy')?>">{{ trans(\Config::get('app.theme').'-app.foot.privacy') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.ethical_code') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.ethical-code')?>">{{ trans(\Config::get('app.theme').'-app.foot.ethical_code') }}</a>
					</li>
					{{--
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.money_laundering_prevention') }}"
					href="{{Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.money_laundering_prevention')}}">{{ trans(\Config::get('app.theme').'-app.foot.money_laundering_prevention') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}"
							href="{{ Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies') }}">{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}</a>
					</li>
					--}}

					<li>
						<a class="footer-link" title="{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.how_to_buy')?>">{{ trans(\Config::get('app.theme').'-app.foot.how_to_buy') }}</a>
					</li>
					<li>
						<button class="footer-link footer-link-button" type="button" data-toggle="modal" data-target="#cookiesPersonalize">
							{{ trans("$theme-app.cookies.configure") }}
						</button>
					</li>
				</ul>

			</div>
		</div>

	</div>
	</div>
</footer>



@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

