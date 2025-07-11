@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.title.artists") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('artist.create', ['menu' => 'subastas']) }}" class="btn btn-primary right">{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.artist") }}</a>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			@csrf
			<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>{{ trans("admin-app.fields.artist.id_artist") }}</th>
						<th>{{ trans("admin-app.fields.artist.name_artist") }}</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>

					</tr>
				</thead>

				<tbody>

					<tr id="filters">
						<form class="form-group" action="">
							<td>{!! $formulario->id_artist !!}</td>
							<td>{!! $formulario->name_artist !!}</td>

							<td class="d-flex gap-5">
								<input type="submit" class="btn btn-info w-100" value="{{ trans("admin-app.button.search") }}">
								<a href="{{route('artist.index', ['menu' => 'subastas'])}}" class="btn btn-warning w-100">{{ trans("admin-app.button.restart") }}</a>
							</td>
						</form>
					</tr>

					@forelse ($webArtist as $artist)

					<tr id="fila{{$artist->id_artist}}">
						<td>{{$artist->id_artist}}</td>
						<td>{{$artist->name_artist}}</td>

						<td class="d-flex w-100 gap-5">
							<a
								@if ($artist->active_artist)
									title="Desactivar"
									estado="on"
									class="btn btn-sm btn-block btn-success jsChangeStatus"
								@else
									title="Activar"
									estado="off"
									class="btn btn-sm btn-block btn-danger jsChangeStatus"
								@endif
									id="{{ $artist->id_artist }}">
										<i class="fa fa-power-off"></i>
							</a>

							<a href="{{ route('artist.edit', $artist->id_artist) }}" class="btn btn-primary btn-sm btn-block mt-0">{{ trans("admin-app.button.edit") }}</a>
							<a href="javascript:deleteArtist('{{$artist->id_artist}}');" class="btn btn-danger btn-sm btn-block mt-0">{{ trans("admin-app.button.delete") }}</a>
						</td>
					</tr>

					@empty

					<tr>
						<td colspan="6"><h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3></td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>
		<div class="col-xs-12 d-flex justify-content-center">
			{{ $webArtist->links() }}
		</div>
	</div>

	@stop
