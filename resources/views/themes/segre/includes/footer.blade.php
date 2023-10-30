<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <h4 class="footer-title">{{ trans($theme . '-app.foot.contact_title') }}</h4>
                <div class="footer-content">
                    {!! trans($theme . '-app.foot.contact_info') !!}
                    <a class="footer-link"
                        href="{{ route('catalogos_newsletter') }}">{{ trans($theme . '-app.foot.receive_catalog') }}</a>
                </div>
                <div class="social-link-container">
                    @if (\Config::get('app.facebook'))
                        <a class="facebook-social-link social-link" href="{{ \Config::get('app.facebook') }}"
                            target="_blank">
                            <i class="fa fa-facebook-square social-link-icon" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (\Config::get('app.twitter'))
                        <a class="twitter-social-link social-link" href="{{ \Config::get('app.twitter') }}"
                            target="_blank">
                            @include('components.x-icon', ['size' => '40'])
                        </a>
                    @endif
                    @if (\Config::get('app.youtube'))
                        <a class="youtube-social-link social-link" href="{{ \Config::get('app.youtube') }}"
                            target="_blank">
                            <i class="fa fa-youtube-square social-link-icon" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (\Config::get('app.instagram'))
                        <a class="instagram-social-link social-link" href="{{ \Config::get('app.instagram') }}"
                            target="_blank">
                            <i class="fa fa-instagram social-link-icon" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if (\Config::get('app.linkedin'))
                        <a class="linkedin-social-link social-link" href="{{ \Config::get('app.linkedin') }}"
                            target="_blank">
                            <i class="fa fa-linkedin-square social-link-icon" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <section>
                    <h4 class="footer-title">{{ trans($theme . '-app.foot.shcedule_title') }}</h4>
                    <div class="footer-content">
                        <p>
                            {!! trans($theme . '-app.foot.segre_schedule') !!}
                        </p>
                    </div>
                </section>
                <section>
                    <h4 class="footer-title">{{ trans($theme . '-app.foot.download_app') }}</h4>
                    <div class="footer-content">
                        <a href="{{ trans($theme . '-app.segre-enlaces.segre_app_apple') }}" target="_blank">
                            <img class=""
                                src="/themes/{{ \Config::get('app.theme') }}/assets/img/1logoapple200.png"
                                alt="" width="200" height="72">
                        </a>

                        <a href="{{ trans($theme . '-app.segre-enlaces.segre_app_android') }}" target="_blank">
                            <img class=""
                                src="/themes/{{ \Config::get('app.theme') }}/assets/img/1logoandroid200.png"
                                alt="" width="200" height="72">
                        </a>
                    </div>
                </section>
            </div>

            <div class="col-xs-12 col-md-4">
                <h4 class="footer-title">{{ trans($theme . '-app.foot.legal') }}</h4>
                <div class="footer-content">
                    <ul>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.general-conditions') }}">
                                {{ trans($theme . '-app.foot.general-conditions') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.legal-warning') }}">
                                {{ trans($theme . '-app.foot.legal-warning') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy_policy') }}">
                                {{ trans($theme . '-app.foot.privacy_policy') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.cookies_policy') }}">
                                {{ trans($theme . '-app.foot.cookies_policy') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link" href="{{ trans($theme . '-app.links.ethical_code') }}"
                                target="_blank">
                                {{ trans($theme . '-app.foot.ethical_code') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link" href="{{ trans($theme . '-app.links.anticorruption_policy') }}"
                                target="_blank">
                                {{ trans($theme . '-app.foot.anticorruption_policy') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.shipping_returns') }}">
                                {{ trans($theme . '-app.foot.shipping_returns') }}
                            </a>
                        </li>
                        <li class="footer-list">
                            <a class="footer-link"
                                href="{{ Routing::translateSeo('pagina') . trans($theme . '-app.links.jewelery_watch_cataloging') }}">
                                {{ trans($theme . '-app.foot.jewelery_watch_cataloging') }}
                            </a>
                        </li>
						<li class="footer-list">
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








<div class="copy color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <p>&copy; <?= trans(\Config::get('app.theme') . '-app.foot.rights') ?> </p>
            </div>
            {{-- <div class="col-xs-12 col-sm-6 social-links">
				<span class="social-links-title">< ?= trans(\Config::get('app.theme') . '-app.foot.follow_us') ?></span>

				<a class="social-link color-letter"><i class="fab fa-2x fa-facebook-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-twitter-square"></i></a>
				&nbsp;
				<a class="social-link color-letter"><i class="fab fa-2x fa-instagram"></i></a>
				<br>
			</div> --}}

            <div class="col-xs-12 col-sm-6 text-right">
                <a class="color-letter" href="{{ trans(\Config::get('app.theme') . '-app.foot.developed_url') }}"
                    title="{{ trans(\Config::get('app.theme') . '-app.foot.developedSoftware') }}" role="button"
                    target="no_blank">{{ trans(\Config::get('app.theme') . '-app.foot.developedBy') }}</a>
            </div>
        </div>
    </div>
</div>

@if (request('recoveryPassword'))
    <script>
        $(document).ready(function() {

            var loader = $('.loader.copyme').clone().removeClass('hidden');

            $('#modalAjax .modal-content .modal-title').html("¿Ha olvidado su contraseña?");


            $.ajax({
                url: "/es/password_recovery",
                dataType: "text",

                beforeSend: function(xhr) {
                    $('#modalAjax .modal-content .modal-body').html(loader);
                },

                success: function(data) {

                    try {

                        info = $.parseJSON(data);

                        if (info.status == 'error') {
                            window.location.href = '/';
                        }

                        //$('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>"+info.msg+"</div>");

                    } catch (e) {
                        // not json
                        $('#modalAjax .modal-content .modal-body').html("<div style='padding:20px;'>" +
                            data + "</div>");
                    }

                    $('#modalAjax .modal-content .loader.copyme').remove();
                    $('#modalAjax').modal("show");
                }
            });
        });
    </script>
@endif

@if (!Cookie::get((new App\Models\Cookies())->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
