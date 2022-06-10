
 <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

	<a id="notAwardExport" class="btn btn-success btn-sm" href="{{route('not_award.export', $idauction)}}">{{ trans('admin-app.button.download_excel') }}</a>

	@include('admin::includes.config_table', ['id' => 'tableNotAwards', 'params' => ((array) $tableParams)])

</div>

	<table id="tableNotAwards" class=" table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order_not_awards">
		<thead>
			<tr>
			@foreach ($tableParams as $param => $display)

				<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif" @if(!in_array($param, ['max_puja', 'max_orden', 'descweb_hces1']) && !in_array($param, $caracteristicas)) data-order="{{$param}}" @endif>
					{{ trans("admin-app.fields.$param") }}
					@if(request()->order_not_awards == $param)
						<span style="margin-left: 5px; float: right;">
							@if(request()->order_not_awards_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							@else
									<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
							@endif
						</span>
					@endif

				</th>

			@endforeach
			<th></th>
		</tr>
	</thead>

	<tbody>
			<tr id="notAwardsFilters">
				<form class="form-group" action="">
					<input type="hidden" name="order_not_awards" value="{{ request('order_not_awards', 'sub_asigl0') }}">
					<input type="hidden" name="order_not_awards_dir" value="{{ request('order_not_awards_dir', 'desc') }}">

					@foreach ($tableParams as $param => $display)
						<td class="{{$param}}" @if(!$display) style="display: none" @endif>

							@if(!empty($formulario->$param) && is_array($formulario->$param))

								@foreach ($formulario->$param as $input)

									{!! $input !!}

								@endforeach

							@else
							{!! $formulario->$param ?? '' !!}
							@endif

						</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info w-100"
							value="{{ trans("admin-app.button.search") }}">
							<a class="btn btn-warning w-100"
								@if($isRender)
									href="{{route(request()->route()->getName(), ['subasta' => $idauction])}}"
								@else
									href="{{route("award.index")}}"
								@endif
							>
							{{ trans("admin-app.button.restart") }}
						</a>
					</td>
				</form>
			</tr>

			@foreach ($lotNotAwards as $lotNotAward)

				<tr id="fila_award_{{$lotNotAward->get('ref_asigl0')}}" style="max-height: 60px; overflow: hidden;">

					@foreach ($tableParams as $param => $display)
					<td class="{{$param}}" @if(!$display) style="display: none" @endif>
						{!! $lotNotAward->get($param) ?? '' !!}
					</td>
					@endforeach
					<td></td>

				</tr>

			@endforeach

		</tbody>

	</table>
	{{ $originalNotAwards->appends(Request::query())->links()}}

