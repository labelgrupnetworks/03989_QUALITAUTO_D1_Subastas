
 <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
	<a id="subConditionsExport" class="btn btn-success btn-sm" href="{{ route('subasta_conditions.download', Request::query()) }}">{{ trans('admin-app.button.download_excel') }}</a>
</div>

	<table id="tableSubConditions" class=" table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order_sub_conditions">
		<thead>
			<tr>
			@foreach ($tableParams as $param => $display)

				<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif">
					{{ trans("admin-app.fields.$param") }}
					@if(request()->order_sub_conditions == $param)
						<span style="margin-left: 5px; float: right;">
							@if(request()->order_type_sub_conditions == 'asc')
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
			<tr id="subConditionsFilters">
				<form class="form-group" action="">
					<input type="hidden" name="order_sub_conditions" value="{{ request('order_sub_conditions', 'fechacreacion_subconditions') }}">
					<input type="hidden" name="order_sub_conditions_dir" value="{{ request('order_sub_conditions', 'desc') }}">

					@foreach ($tableParams as $param => $display)
						<td class="{{$param}}" @if(!$display) style="display: none" @endif>

							@if(!empty($tableFilters->$param) && is_array($tableFilters->$param))

								@foreach ($tableFilters->$param as $input)

									{!! $input !!}

								@endforeach

							@else
							{!! $tableFilters->$param ?? '' !!}
							@endif

						</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info w-100" value="{{ trans("admin-app.button.search") }}">
						<a class="btn btn-warning w-100" href="{{route("subasta_conditions.index")}}">
							{{ trans("admin-app.button.restart") }}
						</a>
					</td>
				</form>
			</tr>

			@foreach ($subConditions as $subCondition)

				<tr id="fila_subcondition_{{$subCondition->id_subconditions}}" style="max-height: 60px; overflow: hidden;">
					<td>
						{{ $subCondition->id_subconditions }}
					</td>
					<td>
						{{ $subCondition->cod_subconditions }}
					</td>
					<td>
						{{ $subCondition->auction->des_sub }}
					</td>
					<td>
						{{ $subCondition->cli_subconditions }}
					</td>
					<td>
						{{ $subCondition->client->rsoc_cli ?? '' }}
					</td>
					<td colspan="2" class="text-center">
						{{ $subCondition->fechacreacion_subconditions }}
					</td>

					<td></td>

				</tr>

			@endforeach

		</tbody>

	</table>
	{{ $subConditions->appends(Request::query())->links()}}

