
<footer>
    <ul class="list-unstyled">
		<li>
			<a class="footer-link"
				href="{{ \Routing::translateSeo('pagina') . trans($theme . '-app.links.privacy') }}">{{ trans($theme . '-app.foot.legal') }}</a>
		</li>
		<li>
			<a class="footer-link"
				href="{{ \Routing::translateSeo('pagina') . trans($theme . '-app.links.term_condition') }}">{{ trans($theme . '-app.foot.term_condition') }}</a>
		</li>
		<li>
			<button class="footer-link footer-link-button" data-toggle="modal"
				data-target="#cookiesPersonalize" type="button">
				{{ trans("$theme-app.cookies.configure") }}
			</button>
		</li>
		<li>
			<a class="footer-link"
				href="{{ \Routing::translateSeo(trans($theme . '-app.links.contact')) }}">{{ trans($theme . '-app.foot.contact') }}</a>
		</li>
	</ul>
</footer>
