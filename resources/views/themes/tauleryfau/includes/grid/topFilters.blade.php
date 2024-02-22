<div class="row">
    <div class="col-xs-12 hidden-xs hidden-sm banner_grid" style="max-height: 82.56px; overflow: hidden;">
        {!! \BannerLib::bannersPorKey('GRID_LOTES', 'banner_grid', '{dots:false, arrows:false, autoplay: true, autoplaySpeed: 5000, slidesToScroll:1}') !!}
    </div>
</div>

<div class="row mt-1 mb-1 hidden-xs hidden-sm">

    <div class="col-xs-12  d-flex align-items-stretch flex-wrap">

		<div class="tags-top-filters d-flex align-items-center">
			@include('front::includes.grid._tags_filters')
		</div>

        <div class="input-order-quantity">
            <select id="total_selected" class="form-control submit_on_change">
                @foreach (\Config::get('app.filter_total_shown_options') as $option)
                    <option value="{{ $option }}" @if (app('request')->input('total') == $option) selected @endif>
                        {{ trans($theme . '-app.lot_list.to_show') }}: {{ $option }}
                    </option>
                @endforeach
            </select>
        </div>


        @if (\Config::get('app.paginacion_grid_lotes'))
            <div class="paginador-container hidden-xs hidden-sm d-flex">
                {{ $paginator->links('front::includes.grid.paginator_pers') }}
            </div>
        @endif

    </div>

</div>
