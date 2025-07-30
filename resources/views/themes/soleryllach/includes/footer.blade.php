<section class="prefooter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="prefooter-title">{{ trans(\Config::get('app.theme') . '-app.foot.followus') }}</div>
                <div class="prefooter-social">
                    <a href="https://www.instagram.com/soleryllach" target="_blank">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="https://twitter.com/soleryllach" target="_blank">
                        @include('components.x-icon', [
                            'sizeX' => '30',
                            'sizeY' => '35',
                            'color' => '#0C2340',
                        ])
                    </a>
                    <a href="https://www.facebook.com/soleryllach" target="_blank">
                        <i class="fa fa-facebook"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="principal-color">

    <div class="container">
        <div class="row">
            <div class="col-xs-12 no-padding">
                <div class="col-xs-12 col-md-3 logo">
                    <img class="img-responsive"
                        src="/themes/{{ \Config::get('app.theme') }}/assets/img/logo_blanc.png?a=1"
                        alt="{{ trans(\Config::get('app.theme') . '-app.home.name') }}" style="height:auto;"
                        height="60" />
                    <ul class="module_wrapper">
                        <li class="module_title secondary-color">
                            <span>{{ trans("$theme-app.foot.in_madrid") }}</span>
                            <address class="mt-0">
                                <a href="tel:+34910773236">+34 91 077 32 36</a><br>
                                <a href="mailto:info@soleryllach.com">info@soleryllach.com</a>
                            </address>
                        <li>

                        <li class="module_title secondary-color">
                            <span>{{ trans("$theme-app.foot.in_barcelona") }}</span>
                            <address class="mt-0">
                                <span>Beethoven 13</span><br>
                                <span>08021 Barcelona</span><br>
                                <a href="tel:+34932018733">+34 93 201 87 33</a><br>
                                <a href="mailto:info@soleryllach.com">info@soleryllach.com</a>
                            </address>
                        <li>
                    </ul>
                </div>

                <div class="col-xs-12 col-md-3">
                    <ul class="module_wrapper">
                        <li class="module_title secondary-color">
                            {{ trans(\Config::get('app.theme') . '-app.foot.schedule_title') }}
                        <li>
                        <li class="li-color" style="color: white; font-size: 12px;">{!! trans(\Config::get('app.theme') . '-app.foot.schedule_content') !!}</li>
                    </ul>


                </div>

                <div class="col-xs-12 col-md-2">
                    <ul class="module_wrapper">
                        <li class="module_title secondary-color">
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
                        <li><a href="<?php echo Routing::translateSeo('pagina') . trans(\Config::get('app.theme') . '-app.links.contact'); ?>"
                                title="{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}">{{ trans(\Config::get('app.theme') . '-app.foot.contact') }}</a>
                        </li>
                        <li><a href="<?= \Routing::translateSeo('especialistas') ?>">
                                {{ trans(\Config::get('app.theme') . '-app.home.specialist-contact') }}</a></li>
                        <li><a href="<?= \Routing::translateSeo('valoracion-articulos') ?>">
                                {{ trans(\Config::get('app.theme') . '-app.home.free-valuations') }}</a></li>
                    </ul>


                </div>
                <div class="col-xs-12 col-md-3">
                    <ul class="module_wrapper">
                        <li class="module_title secondary-color">
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
                        ?>
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
<div class="copy">
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

@if (!Cookie::get(new App\Services\Content\CookieService()->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
