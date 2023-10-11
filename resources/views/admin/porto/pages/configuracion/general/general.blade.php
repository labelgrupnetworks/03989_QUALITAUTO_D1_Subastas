@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>Configuración</h1>
            </div>
        </div>

        <form action="{{ route('admin.configuracion.save') }}" method="POST">
			@csrf
            <div class="row well">
                <div class="col-xs-12 text-right mb-1 pt-1 pb-1">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('admin-app.title.save') }}
                    </button>
                </div>

                <div class="">
                    <table class="bloque-conf table table-bordered table-striped mb-none no-footer"
                        id="datatable-default" role="grid" aria-describedby="datatable-default_info">

                        <tbody>

                            <tr role="row" class="odd">
                                <td style="width: 75%">Deshabilitar registro</td>
                                <td class="text-center">
                                    <select class="form-control" id="select_registration" name="registration_disabled">
                                        @if ($data['registration_disabled'])
                                            <option value="1">Si</option>
                                            <option value="0" selected>No</option>
                                        @else
                                            <option value="1" selected>Si</option>
                                            <option value="0">No</option>
                                        @endif
                                    </select>
                                </td>
                            </tr>

							@if(isset($data['buyer_premium_active']))
								<tr role="row" class="odd">
									<td style="width: 75%">Comisión de comprador</td>
									<td class="text-center">
										<select class="form-control" id="select_registration" name="buyer_premium_active">
											<option value="1" {{ $data['buyer_premium_active'] ? 'selected' : '' }}>Si</option>
											<option value="0" {{ $data['buyer_premium_active'] ? '' : 'selected' }}>No</option>
										</select>
									</td>
								</tr>
							@endif

							@if(isset($data['addComisionEmailBid']))
								<tr role="row" class="odd">
									<td style="width: 75%">Porcentaje comisión de puja</td>
									<td class="text-center">
										<input type="number" class="form-control" name="addComisionEmailBid" value="{{ $data['addComisionEmailBid'] }}">
									</td>
								</tr>
							@endif

                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </section>

@stop
