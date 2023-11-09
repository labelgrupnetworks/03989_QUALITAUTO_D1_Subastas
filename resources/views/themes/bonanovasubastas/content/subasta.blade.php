<script>
    var cod_sub = @json($data['cod_sub']);
	routing = {
		...routing,
		node_url: @json(Config::get('app.node_url')),
		comprar: @json($data['node']['comprar']),
		ol: @json($data['node']['ol'])
	}
</script>

<div class="container">
    @include('includes.subasta.layout_options')
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            @include('includes.subasta.filters')
        </div>

        <div class="col-xs-12 col-sm-8 col-md-9">
            <div class="list_lot">
                @include('includes.subasta.lots')
            </div>
        </div>

		<div class="capaOculta">
			<img>
		</div>

        <div class="col-xs-12 col-md-8 col-md-offset-3 col-xs-offset-0">
			 {!! $data['subastas.paginator'] !!}
        </div>
    </div>
</div>

@if (!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
    <div class="container category">
        <div class="row">
            <div class="col-lg-12">
                 {!! $data['seo']->meta_content !!}
            </div>
        </div>
    </div>
@endif
