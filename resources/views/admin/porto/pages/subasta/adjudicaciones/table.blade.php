
 <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

	<a id="awardExport" class="btn btn-success btn-sm" href="{{route('award.export', $idauction)}}">{{ trans('admin-app.button.download_excel') }}</a>

	<a href="{{ route('award.create', ['idAuction' => $idauction]) }}" class="btn btn-primary btn-sm">
		{{ trans("admin-app.button.new_fem") }} {{ trans("admin-app.title.award") }}
	</a>

	@include('admin::includes.config_table', ['id' => 'tableAwards', 'params' => ((array) $tableParams)])

</div>

	<table id="tableAwards" class=" table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order_awards">
		<thead>
			<tr>
			@foreach ($tableParams as $param => $display)

				<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif" @if(!in_array($param, ['max_puja', 'max_orden', 'descweb_hces1']) && !in_array($param, $caracteristicas)) data-order="{{$param}}" @endif>
					{{ trans_choice("admin-app.fields.$param", $tipo_sub ?? '') }}
					@if(request()->order_awards == $param)
						<span style="margin-left: 5px; float: right;">
							@if(request()->order_awards_dir == 'asc')
								<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
							@else
									<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
							@endif
						</span>
					@endif

				</th>

			@endforeach
			<th>
				<span>{{ trans("admin-app.fields.actions") }}</span>
			</th>
		</tr>
	</thead>

	<tbody>
			<tr id="awardsFilters">
				<form class="form-group" action="">
					<input type="hidden" name="order_awards" value="{{ request('order_awards', 'sub_asigl0') }}">
					<input type="hidden" name="order_awards_dir" value="{{ request('order_awards_dir', 'desc') }}">

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

			@foreach ($awards as $award)

				<tr id="fila_award_{{$award->get('ref_asigl0')}}" style="max-height: 60px; overflow: hidden;">

					@foreach ($tableParams as $param => $display)
					<td class="{{$param}}" @if(!$display) style="display: none" @endif>
						{!! $award->get($param) ?? '' !!}
					</td>
					@endforeach

					<td>
						<a title="{{ trans("admin-app.button.edit") }}"
							href="{{ route('award.edit', ['idauction' => $award->get('sub_asigl0'), 'ref' => $award->get('ref_asigl0'), 'licit' => $award->get('licit_csub')]) }}"
							class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i>{{ trans("admin-app.button.edit") }} {{ trans('admin-app.title.award') }}
						</a>
					</td>

				</tr>

			@endforeach

		</tbody>

	</table>
	{{ $originalAwards->appends(Request::query())->links()}}

