<div class="footer2 container mt-2">
	<ul class="ul-format footer-second-ul d-flex flex-wrap align-items-center">
		<li>
			<a class="footer-link" title="{{ trans($theme.'-app.foot.faq')}}"
			href="/{{\Config::get('app.locale')}}/{{ trans(\Config::get('app.theme').'-app.links.faq') }}"><span>{{ trans($theme.'-app.foot.faq')}}</span>
			</a>
		</li>

		<li>
			<a class="footer-link" title="{{ trans($theme.'-app.home.blog')}}"
				href="{{ Routing::translateSeo('blog') }}"> <span>Prensa</span>
			</a>
		</li>

		<li>
			<a class="footer-link"
				title="{{ trans($theme.'-app.foot.term_condition') }}"
				href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.term_condition') }}">
				<span>{{ trans($theme.'-app.foot.term_condition') }}</span>
			</a>
		</li>


		<li>
			<a class="footer-link" title="{{ trans($theme.'-app.foot.privacy')}}"
				href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.privacy') }}"><span>{{ trans($theme.'-app.foot.privacy')}}</span>
			</a>
		</li>

		<li>
			<a class="footer-link" title="{{ trans($theme.'-app.foot.cookies')}}"
				href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.cookies') }}"><span>{{ trans($theme.'-app.foot.cookies')}}</span>
			</a>
		</li>

		<li>
			<a class="footer-link" title="{{ trans($theme.'-app.foot.general_conditions')}}"
				href="{{ Routing::translateSeo('pagina').trans($theme.'-app.links.general_conditions') }}"><span>{{ trans($theme.'-app.foot.general_conditions')}}</span>
			</a>
		</li>

	</ul>
</div>


<div class="copy color-letter">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center footer-text-black">
				<p class="m-0">&copy; {!! trans($theme.'-app.foot.rights') !!} </p>
			</div>
		</div>
	</div>
</div>

@if (!Cookie::get("cookie_config"))
	@include("includes.cookie")
@endif

<script>
	let domain = window.location.hostname;
</script>

@if (empty($cookiesState['google']) && empty($cookiesState['all']))
<script>
	deleteGoogleCookies(domain);

	if(domain.includes('www')){
		deleteGoogleCookies(domain.split('www')[1]);
	}
</script>
@endif

@if (empty($cookiesState['facebook']) && empty($cookiesState['all']))
<script>
	deleteFacebookCookies(domain);

	if(domain.includes('www')){
		deleteFacebookCookies(domain.split('www')[1]);
	}
</script>
@endif
