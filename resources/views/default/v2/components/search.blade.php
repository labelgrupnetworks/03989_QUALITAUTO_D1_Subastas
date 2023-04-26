<div class="search-component-wrapper {{ $classes }} me-auto">
	<form role="search" action="{{ \Routing::slug('busqueda') }}">
		<div class="input-group">
			<input class="form-control form-control-sm" type="search" name="texto" placeholder="{{ trans("$theme-app.head.search_label") }}" aria-label="{{ trans("$theme-app.head.search_label") }}" aria-describedby="button-addon2">
			<button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
				<svg class="bi" width="16" height="16" fill="currentColor">
					<use xlink:href="/bootstrap-icons.svg#search"></use>
				</svg>
			</button>
		</div>
	</form>
</div>
