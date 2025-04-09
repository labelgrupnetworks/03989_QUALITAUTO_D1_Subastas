
 <footer>
	<div class="container-fluid text-center">

		<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="footer-title text-uppercase">
					{{ trans($theme.'-app.foot.conditions') }}
				</div>
				<ul class="ul-format footer-ul">
					<li><a class="footer-link"
							title="{{ trans($theme.'-app.foot.about_us') }}"
							href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.general_conditions') }}">{{ trans($theme.'-app.foot.general_conditions') }}</a>
					</li>
					{{-- <li>
						<a class="footer-link" title="{{ trans($theme.'-app.foot.contact')}}"
							href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.shipments') }}"><span>{{ trans($theme.'-app.foot.shipments')}}</span></a>
					</li> --}}
					<li>
						<a class="footer-link"
							href="<?= Routing::translateSeo('preguntas-frecuentes') ?>"><span>{{ trans($theme.'-app.foot.faq') }}</span></a>
					</li>
					{{-- <li><a class="footer-link" title="{{ trans($theme.'-app.foot.faq')}}"
							href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.refund_withdrawal') }}"><span>{{ trans($theme.'-app.foot.refund_withdrawal')}}</span></a>
					</li> --}}
				</ul>
			</div>

			<div class="col-xs-12 col-md-4">
				<div class="footer-title text-uppercase">
					{{ trans($theme.'-app.foot.enterprise') }}
				</div>
				<ul class="ul-format footer-ul">
					<li><a class="footer-link"
							title="{{ trans($theme.'-app.foot.about_us') }}"
							href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.about_us') }}">{{ trans($theme.'-app.foot.about_us') }}</a>
					</li>
					<li>
						<a class="footer-link" title="{{ trans($theme.'-app.foot.contact')}}"
							href="{{ Routing::translateSeo(trans($theme.'-app.links.contact')) }}"><span>{{ trans($theme.'-app.foot.contact')}}</span></a>
					</li>
					{{-- <li><a class="footer-link" title="{{ trans($theme.'-app.foot.faq')}}"
							href="{{ Routing::translateSeo(trans($theme.'-app.links.faq')) }}"><span>{{ trans($theme.'-app.foot.faq')}}</span></a>
					</li> --}}
				</ul>
			</div>

			<div class="col-xs-12 col-md-4">
				<div class="footer-title text-uppercase">
					{{ trans($theme.'-app.foot.term_condition')}}
				</div>
				<ul class="ul-format footer-ul">
					{{-- <li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.term_condition') }}"
							href="< ?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition')?>">{{ trans($theme.'-app.foot.term_condition') }}</a>
					</li> --}}
					<li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.privacy') }}"
							href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.privacy')?>">{{ trans($theme.'-app.foot.privacy') }}</a>
					</li>
					<li>
						<a class="footer-link"
							title="{{ trans($theme.'-app.foot.cookies') }}"
							href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.cookies')?>">{{ trans($theme.'-app.foot.cookies') }}</a>
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
</footer>

<div class="copy color-letter text-center">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<p>&copy; <?= trans($theme.'-app.foot.rights') ?> </p>
			</div>

			<div class="col-xs-12">
				<a class="color-letter" role="button"
					title="{{ trans($theme.'-app.foot.developedSoftware') }}"
					href="{{ trans($theme.'-app.foot.developed_url') }}"
					target="no_blank">{{ trans($theme.'-app.foot.developedBy') }}</a>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get((new App\Models\Cookies)->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
