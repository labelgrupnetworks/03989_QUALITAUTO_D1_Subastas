<footer>
	<div class="container-fluid">
		<div class="row p-1 mb-3 gray-background" style="height: 60px">
			<div class="col-xs-12">
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-6">
				<div class="row">

					<div class="col-xs-12 text-center">

						<div class="footer-title">
							{{ trans($theme.'-app.foot.term_condition')}}
						</div>
						<ul class="ul-format footer-ul">
							<li>
								<a class="footer-link"
									title="{{ trans($theme.'-app.foot.legal-warning') }}"
									href="<?php echo Routing::translateSeo('pagina').trans($theme.'-app.links.legal-warning')?>">{{ trans($theme.'-app.foot.legal-warning') }}</a>
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

			<div class="col-xs-12 col-lg-6">
				<div class="row footer-title">
					<div class="col-xs-12 image text-center">
						<img class="logo-company" src="/themes/{{$theme}}/assets/img/logo_footer.png"
							alt="{{(\Config::get( 'app.name' ))}}" width="90%">

						<p class="mt-1"><a href="mailto:info@tilesales.com" class="footer-link" style="font-weight: 400">info@tilesales.es</a></p>
					</div>


				</div>
			</div>
		</div>
	</div>
</footer>

@if (!Cookie::get((new App\Services\Content\CookieService)->getCookieName()))
    @include('includes.cookie', ['style' => 'popover'])
@endif

@include('includes.cookies_personalize')
