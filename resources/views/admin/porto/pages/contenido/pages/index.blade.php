@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-12">
			<h1>{{ trans_choice("admin-app.title.page", 2) }}</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

			<a href="{{ route('static-pages.create') }}" class="btn btn-sm btn-primary">{{ trans("admin-app.button.new_fem") }}
				{{ trans_choice("admin-app.title.page", 1) }}</a>

			@include('admin::includes.config_table', ['id' => 'pages', 'params' => $tableParams])
		</div>

		<div class="col-xs-12 table-responsive">
			<table id="pages" class="table table-striped table-condensed table-responsive" style="width:100%" data-order-name="order">
				<thead>
					<tr>
						@foreach ($tableParams as $param => $display)

					<th class="{{$param}}"  style="cursor: pointer; @if(!$display) display: none; @endif" data-order="{{$param}}">
						{{ trans("admin-app.fields.$param") }}
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

					@forelse ($pages as $page)

					<tr id="{{$page->id_web_page}}">
						@foreach ($tableParams as $param => $display)
							<td class="{{$param}}" @if(!$display) style="display: none" @endif>
								{!! $page->$param ?? '' !!}
							</td>
						@endforeach

						<td>
							<a href="{{ $page->url_page }}" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a
								title="{{ trans("admin-app.button.edit") }}"
								href="{{ route('static-pages.edit', $page->id_web_page) }}"
								class="btn btn-success btn-sm">

							<i class="fa fa-pencil-square-o"
								aria-hidden="true"></i>{{ trans("admin-app.button.edit") }}</a>

							<button
								class="js-delete_page btn btn-danger btn-sm"
								data-toggle="modal" data-target="#deleteModal"
								data-id="{{ $page->id_web_page }}" data-name="{{ trans('admin-app.title.delete_page', ['id' => $page->id_web_page ]) . ' - ' . $page->name_web_page }}">
								{{ trans("admin-app.button.delete") }} </button>

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

	</div>

	@include('admin::includes._delete_modal', ['routeToDelete' => route('static-pages.destroy', 0)])

</section>

<script>
	$('#deleteModal').on('show.bs.modal', function (event) {

		var button = $(event.relatedTarget);
		var id = button.data('id');
		var name = button.data('name');

		//obtenemos el id del data action del form
		var action = $('#formDelete').attr('data-action').slice(0, -1) + id;
		$('#formDelete').attr('action', action);

		var modal = $(this);
		modal.find('.modal-title').text(name);
	});
</script>

@stop
