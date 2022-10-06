<div class="search-component  hidden-xs">
	<form role="search" action="/es/busqueda" class="search-component-form">
		<div class="form-group">
			<input class="form-control input-custom"
				placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" autocomplete="off">
		</div>
		<button type="submit" class="btn btn-custom-search-large"><i class="fa fa-search"></i>
			<div style="display: none;top:0;" class="loader mini"></div>
		</button>
	</form>
</div>

<div class="hidden-sm hidden-md hidden-lg">
	<a role="search" href="/es/busqueda" class="">
		<button class="btn search-mobile"><i class="fa fa-search"></i>
			<div style="display: none;top:0;" class="loader mini"></div>
		</button>
	</a>
</div>
