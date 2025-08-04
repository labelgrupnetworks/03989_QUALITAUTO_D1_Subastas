<section class="prefooter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div>
                    <span style="font-size: 45px;"></span>

                    <img src="/themes/{{ \Config::get('app.theme') }}/img/logo_prefooter.jpg"
                        alt="{{ trans(\Config::get('app.theme') . '-app.home.name') }}" />

                </div>
            </div>
        </div>
    </div>
</section>

<footer class="gray-bgcolor-4 black-color">

    <div class="container">
        <div class="row">
            <div class="col-xs-12 no-padding">

                <div class="col-xs-12 col-md-4">
					<div class="module_header module_title secondary-color visible-xs" data-toggle="collapse" href="#collapse-schedule">
						{{ trans(\Config::get('app.theme') . '-app.foot.schedule') }}
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
					</div>

                    <ul id="collapse-schedule" class="module_wrapper collapse">
                        <li class="module_title black-color font-bold hidden-xs">
							{{ trans("$theme-app.foot.schedule") }}
                        <li>
                        <li class="gray-color-5">{!! trans("$theme-app.foot.weekday_hours") !!}</li>
                    </ul>
                </div>

                <div class="col-xs-12 col-md-4">
					<div class="module_header module_title secondary-color visible-xs" data-toggle="collapse" href="#collapse-enterprise">
						{{ trans(\Config::get('app.theme') . '-app.foot.enterprise') }}
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
					</div>

                    <ul id="collapse-enterprise" class="module_wrapper collapse">
                        <li class="module_title black-color font-bold hidden-xs">
                            {{ trans(\Config::get('app.theme') . '-app.foot.enterprise') }}
                        <li>
                        <li><a
                                href="{{ \Routing::translateSeo('todas-subastas') }}?finished=false">{{ trans(\Config::get('app.theme') . '-app.foot.auctions') }}</a>
                        </li>
                        @if (!empty($has_subasta) && $finished)
                            <li class="li-color"><a
                                    href="{{ \Routing::translateSeo('todas-subastas') }}?finished=true">{{ trans(\Config::get('app.theme') . '-app.foot.auctions-finished') }}</a>
                            </li>
                        @endif
                        <li><a href="<?php echo Routing::translateSeo('subastas-historicas'); ?>"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}">{{ trans(\Config::get('app.theme') . '-app.foot.historico') }}</a>
                        </li>
                        <li><a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.about_us'); ?>"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.about_us') }}">{{ trans(\Config::get('app.theme') . '-app.foot.about_us') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('contact_page') }}"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}">
                                {{ trans(\Config::get('app.theme') . '-app.foot.contact') }}
                            </a>
                        </li>
                        <li><a href="<?= \Routing::translateSeo('valoracion-articulos') ?>">
                                {{ trans(\Config::get('app.theme') . '-app.home.free-valuations') }}</a></li>
                    </ul>
                </div>


                <div class="col-xs-12 col-md-4">

					<div class="module_header module_title secondary-color visible-xs" data-toggle="collapse" href="#collapse-term_condition">
						{{ trans(\Config::get('app.theme') . '-app.foot.term_condition') }}
						<span class="glyphicon glyphicon-chevron-down pull-right"></span>
					</div>

                     <ul id="collapse-term_condition" class="module_wrapper collapse">
                        <li class="module_title black-color font-bold hidden-xs">
                            {{ trans(\Config::get('app.theme') . '-app.foot.term_condition') }}
                        <li>
                        <li><a
                                href="<?= Routing::translateSeo('preguntas-frecuentes') ?>">{{ trans(\Config::get('app.theme') . '-app.foot.faq') }}</a>
                        </li>
                        <li><a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.privacy'); ?>"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.privacy') }}">{{ trans(\Config::get('app.theme') . '-app.foot.privacy') }}</a>
                        </li>
                        <?php
                        /*<li><a title="{{ trans(\Config::get('app.theme').'-app.foot.cookies') }}" href="<?php echo Routing::translateSeo('pagina').trans(\Config::get('app.theme').'-app.links.cookies')?>
                        ?>">{{ trans(\Config::get('app.theme') . '-app.foot.cookies') }}</a></li>
                        */?>
                        <li><a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.term_condition'); ?>"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.term_condition') }}">{{ trans(\Config::get('app.theme') . '-app.foot.term_condition') }}</a>
                        </li>
                        <li>
                            <button class="footer-link footer-link-button" data-toggle="modal"
                                data-target="#cookiesPersonalize" type="button">
                                {{ trans("$theme-app.cookies.configure") }}
                            </button>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
</footer>
<div class="gray-bgcolor-4 black-color">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12" style="display: flex; flex-wrap: wrap">
                <p style="margin: 5px auto 5px 0px;">
                    <span>&copy; <?= trans(\Config::get('app.theme') . '-app.foot.rights') ?></span>
                </p>
                <p style="margin: 5px 0px;"><a class="color-letter"
                        href="{{ trans(\Config::get('app.theme') . '-app.foot.developed_url') }}"
                        title="{{ trans(\Config::get('app.theme') . '-app.foot.developedSoftware') }}" role="button"
                        target="no_blank">{{ trans(\Config::get('app.theme') . '-app.foot.developedBy') }}</a></p>
            </div>
        </div>
    </div>
</div>

@if (!Cookie::get((new App\Services\Content\CookieService())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
