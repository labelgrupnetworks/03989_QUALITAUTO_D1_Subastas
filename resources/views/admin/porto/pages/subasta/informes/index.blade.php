@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Informes</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12">
                <table class="table table-striped table-condensed table-responsive" style="width:100%;" data-order-name="order">
                    <thead>
                        <tr>
                            <th class="cod_sub" style="cursor: pointer; width: 20ch;" data-order="cod_sub">
                                {{ trans('admin-app.fields.cod_sub') }}
                                @if (request()->order == 'cod_sub')
                                    <span style="margin-left: 5px; float: right;">
                                        @if (request()->order_dir == 'asc')
                                            <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                @endif
                            </th>
                            <th class="des_sub" style="cursor: pointer" data-order="des_sub">
                                {{ trans('admin-app.fields.des_sub') }}
                                @if (request()->order == 'des_sub')
                                    <span style="margin-left: 5px; float: right;">
                                        @if (request()->order_dir == 'asc')
                                            <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
                                        @else
                                            <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
                                        @endif
                                    </span>
                                @endif
                            </th>
                            <th>
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr id="filters">
                            <form class="form-group" action="">
                                <input type="hidden" name="order" value="{{ request('order', 'dfec_sub') }}">
                                <input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">
                                <td class="cod_sub">{!! $filters->cod_sub !!}</td>
                                <td class="des_sub">{!! $filters->des_sub !!}</td>

                                <td class="d-inline-flex justify-content-flex-end" style="gap: 2px;">
                                    <button type="submit" class="btn btn-info w-100">{{ trans('admin-app.button.search') }}</button>

                                    <a href="{{ route('subasta.reports.index') }}" class="btn btn-warning w-100">
                                        {{ trans('admin-app.button.restart') }}
                                    </a>
                                </td>
                            </form>
                        </tr>

                        @forelse ($auctions as $auction)
                            <tr id="fila{{ $auction->cod_sub }}">
                                <td class="cod_sub">{{ $auction->cod_sub }}</td>
                                <td class="des_sub">{{ $auction->des_sub }}</td>

                                <td class="d-inline-flex w-100" style="gap: 2px;">
                                    <button class="btn btn-success btn-sm" onclick="generateReport(this, '{{ $auction->cod_sub }}')">
                                        Generar informes
										<span class="loa"></span>
                                    </button>
                                    <a class="btn btn-primary btn-sm" href="{{ route('subasta.reports.download', ['cod_sub' => $auction->cod_sub]) }}" download="">
                                        Descargar informes
                                    </a>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="col-xs-12 d-flex justify-content-center">
                {{ $auctions->appends(Request::query())->links() }}
            </div>
        </div>

    </section>


<script>
	function generateReport(button, cod_sub) {
		let originalText = $(button).text();

		$.ajax({
			url: "{{ route('subasta.reports.generate') }}",
			type: 'POST',
			data: {
				_token: $('input[name="_token"]').val(),
				cod_sub: cod_sub
			},
			beforeSend: function () {
				$(button).text('Generando...').addClass('disabled');
			},
			success: function (response) {
				saved(response.message);
			},
			error: function (errorResponse) {
				error(errorResponse.responseJSON.message);
			},
			complete: function () {
				$(button).text(originalText).removeClass('disabled');
			}
		});
	}
</script>
@stop
