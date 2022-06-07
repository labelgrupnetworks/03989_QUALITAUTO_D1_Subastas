            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

	{{-- <a href="{{ route("providers.create") }}" class="btn btn-sm btn-primary">
		{{ trans("admin-app.button.new") }} {{ trans_choice("admin-app.title.provider", 0) }}
	</a> --}}

	<a href="{{ route("articles.order_edit") }}"
	class="btn btn-sm btn-warning">{{ trans("admin-app.button.sort") }}
	{{ trans("admin-app.title.articles") }}</a>

	@include('admin::includes.config_table', ['id' => 'tablearticles', 'params' => $tableParams])

</div>

<div class="col-xs-12 table-responsive">
	<table id="tablearticles" class="table table-striped table-condensed" style="width:100%"
		data-order-name="order">
		<thead>
			<tr>

				@foreach ($tableParams as $param => $display)

				<th class="{{$param}}" style="cursor: pointer; @if(!$display) display: none; @endif"
					data-order="{{$param}}">
					{{ trans("admin-app.fields.articles.$param") }}
					@if(request()->order == $param)
					<span style="margin-left: 5px; float: right;">
						@if(request()->order_dir == 'asc')
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
			<tr id="filters">
				<form class="form-group" action="">
					<input type="hidden" name="order" value="{{ request('order', 'cod_pro') }}">
					<input type="hidden" name="order_dir" value="{{ request('order_dir', 'asc') }}">

					@foreach ($tableParams as $param => $display)
					 <td class="{{$param}}" @if(!$display) style="display: none" @endif>{!! $formulario->$param ?? '' !!}</td>
					@endforeach

					<td class="d-flex">
						<input type="submit" class="btn btn-info" value="{{ trans("admin-app.button.search") }}">
						<a href="{{route("articles.index")}}"
							class="btn btn-warning">{{ trans("admin-app.button.restart") }}
						</a>
					</td>
				</form>
			</tr>

			@forelse ($articles as $article)

			<tr id="fila{{$article->id_art0}}" style="max-height: 60px; overflow: hidden;">

				@foreach ($tableParams as $param => $display)

				<td class="{{$param}}" @if(!$display) style="display: none" @endif>
					{!! $article->$param ?? '' !!}
				</td>

				@endforeach

				<td>
					{{-- <a title="{{ trans("admin-app.button.edit") }}"
						href="{{ route("providers.edit", ['id_art0' => $article->id_art0] ) }}"
						class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						{{ trans("admin-app.button.edit") }}
					</a>

					<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"
								data-id="{{ $article->id_art0 }}" data-name="{{ trans('admin-app.title.delete_provider', ['id' => $article->id_art0 ]) . ' - ' . $article->sec_art0 }}">
								<i class="fa fa-trash"></i>
					</button> --}}
				</td>
			</tr>

			@empty

			<tr>
				<td colspan="6">
					<h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3>
				</td>
			</tr>

			@endforelse
		</tbody>
	</table>

</div>
<div class="col-xs-12 d-flex justify-content-center">
	{{ $articles->links() }}
</div>
