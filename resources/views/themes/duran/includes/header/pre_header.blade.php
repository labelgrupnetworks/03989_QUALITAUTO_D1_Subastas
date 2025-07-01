@php
	use App\libs\TradLib;
    use App\Support\Localization;
@endphp
<div class="lang-selection">
    <div class="social-container">
        <div class="row">
            <div class="col-xs-12 text-right d-flex justify-content-flex-end">

                @foreach (Localization::getAvailableLocales() as $key)
                    <ul class="ul-format list-lang d-inline-flex">

                        @if (\App::getLocale() != $key)

							<?php
                            #Obtener la ruta en el idioma contrario segun las tablas seo y/o traducciones links
                            $ruta = TradLib::getRouteTranslate(substr($_SERVER['REQUEST_URI'], 4), \App::getLocale(), $key);
                            ?>
                            <li>
                                <a class="link-lang color-letter" href="/<?= $key . $ruta ?>"
                                    title="<?= trans($theme . '-app.head.language_es') ?>" translate="no">
                                    <span translate="no">{{ trans($theme . '-app.home.' . $key) }}</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="link-lang active color-letter"
                                    title="<?= trans($theme . '-app.head.language_es') ?>" translate="no">
                                    <span translate="no">{{ trans($theme . '-app.home.' . $key) }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                @endforeach


                <ul class="ul-format list-lang d-inline-flex redes-sociales">
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
                            @include('components.x-icon', ['size' => '12'])
                        </a>
                    </li>
                    <li class="youtube">
                        <a href="https://www.youtube.com/channel/UCKWEKBgBba5RGYaDRiSdHDA/videos" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </li>
                    <li class="email">
                        <a href="mailto:duran@duran-subastas.com" target="_blank">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
