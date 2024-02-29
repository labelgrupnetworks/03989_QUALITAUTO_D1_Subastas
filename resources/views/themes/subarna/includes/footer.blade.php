@php
	$existHistorica = $global['subastas']->has('H');
	$existPresencial = $global['subastas']->has('S') && $global['subastas']['S']->has('W');

	$urlPresencial="#";
	if($existPresencial && $global['subastas']['S']['W']->count() == 1){
		$subasta = $global['subastas']['S']['W']->flatten()->first();
		$urlPresencial = Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
	} elseif($existPresencial && $global['subastas']['S']['W']->count() > 1){
		$urlPresencial = Routing::translateSeo('presenciales');
	}
@endphp
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">

                <div class="row">

                    <div class="col-xs-6 col-md-3 mb-3 text-sm-center">
                        <div class="footer-title">
                            <p><b>{{ trans($theme . '-app.subastas.auctions') }}</b></p>
                        </div>
                        <ul class="ul-format footer-ul">
							@if ($existPresencial)
							<li>
								<a class="footer-link" href="{{ $urlPresencial }}">{{ trans($theme.'-app.foot.auctions')}}</a>
							</li>
							@endif

							@if ($existHistorica)
							<li>
								<a class="footer-link" href="{{ \Routing::translateSeo('subastas-historicas') }}">{{ trans($theme.'-app.foot.historico')}}</a>
							</li>
							@endif

                            <li>
								<a class="footer-link" href="{{ route('allCategories', ['typeSub' => 'P']) }}">{{ trans($theme.'-app.foot.online_auction')}}</a>
							</li>
                        </ul>
                    </div>

                    <div class="col-xs-6 col-md-3 mb-3 text-sm-center">
                        <div class="footer-title">
                            <p><b>{{ trans($theme . '-app.foot.enterprise') }}</b></p>
                        </div>
                        <ul class="ul-format footer-ul">
                            <li>
                                <a class="footer-link"
                                    href="{{ \Routing::translateSeo('pagina') . trans($theme . '-app.links.about_us') }}">{{ trans($theme . '-app.foot.about_us') }}</a>
                            </li>
                            <li>
                                <a class="footer-link"
                                    href="{{ \Routing::translateSeo(trans($theme . '-app.links.contact')) }}">{{ trans($theme . '-app.foot.contact') }}</a>
                            </li>
                            <li>
                                <a class="footer-link"
                                    href="{{ \Routing::translateSeo('valoracion-articulos') }}">{{ trans($theme . '-app.home.free-valuations') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-6 col-md-3 mb-3 text-sm-center">
                        <div class="footer-title">
                            <p><b>{{ trans($theme . '-app.foot.term_condition') }}</b></p>
                        </div>
                        <ul class="ul-format footer-ul">
                            <li>
                                <a class="footer-link"
                                    href="{{ \Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy') }}">{{ trans($theme . '-app.foot.legal') }}</a>
                            </li>
                            <li>
                                <a class="footer-link"
                                    href="{{ \Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}">{{ trans($theme . '-app.foot.term_condition') }}</a>
                            </li>
							<li>
								<button class="footer-link footer-link-button" type="button" data-toggle="modal" data-target="#cookiesPersonalize">
									{{ trans("$theme-app.cookies.configure") }}
								</button>
							</li>
                        </ul>
                    </div>
                    <div class="col-xs-6 col-md-3 mb-3 text-sm-center">
                        <?php
                        $empre = new \App\Models\Enterprise();
                        $empresa = $empre->getEmpre();
                        ?>
                        <div class="footer-title">
                            <p><b>{{ $empresa->nom_emp ?? '' }}</b></p>
                        </div>
                        <ul class="ul-format footer-ul">
                            <li>
                                <span class="footer-link">{{ $empresa->dir_emp ?? '' }}</span>
                            </li>
                            <li>
                                <span class="footer-link">{{ $empresa->cp_emp ?? '' }}</span> <span
                                    class="footer-link">{{ $empresa->pob_emp ?? '' }}</span>
                            </li>
                            <li>
                                <span class="footer-link">{{ $empresa->tel1_emp ?? '' }}</span> <a
                                    href="mailto:{{ $empresa->email_emp ?? '' }}"
                                    class="footer-link">{{ $empresa->email_emp ?? '' }}</span>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>

        </div>

    </div>
</footer>

<div class="copy">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <p>&copy; <?= trans($theme . '-app.foot.rights') ?> </p>
            </div>
            <div class="col-xs-12">
                <a class="color-letter" role="button"
                    title="{{ trans($theme . '-app.foot.developedSoftware') }}"
                    href="{{ trans($theme . '-app.foot.developed_url') }}"
                    target="no_blank">{{ trans($theme . '-app.foot.developedBy') }}</a>
            </div>
        </div>
    </div>
</div>


@if (!Cookie::get((new App\Models\Cookies)->getCookieName()))
	@include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')

