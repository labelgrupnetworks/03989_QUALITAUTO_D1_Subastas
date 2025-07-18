<!doctype html>
<html lang="{{config('app.locale')}}">
<head>
	@include('includes.open_graph_head')
    @include('includes.google_head')
    @include('includes.head')
</head>

<body>

     @include('includes.google_body')
        @include('includes.header')

        @yield('content')

        @include('includes.newsletter')

        @include('includes.footer')

		{!! App\Services\Content\ManagementService::render() !!}

        @include('includes.modals')

<div class="button-up-search">
	<i class="fa fa-search" aria-hidden="true"></i>
	<div class="search-input" style="display: none">
		<form id="formsearch-responsive" role="search" action="{{ \Routing::slug('busqueda') }}">
			<div class="form-group" style="padding-right: 0;">
				<input class="form-control input-custom" placeholder="{{ trans(\Config::get('app.theme').'-app.head.search_label') }}" type="text" name="texto" id="textSearch">
					<button type="submit" class="btn btn-custom-search" style="right:3px;">
						<i class="fa fa-search"></i>
					</button>
				</div>
		</form>
	</div>
</div>
<div class="button-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>
</div>
 <?php Tools::querylog(); ?>
</body>

</html>
