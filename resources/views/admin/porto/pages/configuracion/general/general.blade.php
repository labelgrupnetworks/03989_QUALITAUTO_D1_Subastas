@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1>{{ trans("admin-app.title.config") }}</h1>
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
                                <td style="width: 75%">{{ trans("admin-app.fields.disable_register") }}</td>
                                <td class="text-center">
                                    <select class="form-control" id="select_registration" name="registration_disabled">
                                        <option value="1" {{ $data['registration_disabled'] ? '' : 'selected' }}>{{ trans("admin-app.fields.yes") }}</option>
                                        <option value="0" {{ $data['registration_disabled'] ? 'selected' : '' }}>{{ trans("admin-app.fields.no") }}</option>
                                    </select>
                                </td>
                            </tr>

							@if(isset($data['buyer_premium_active']))
								<tr role="row" class="odd">
									<td style="width: 75%">{{ trans("admin-app.fields.buyer_comision") }}</td>
									<td class="text-center">
										<select class="form-control" id="select_registration" name="buyer_premium_active">
											<option value="1" {{ $data['buyer_premium_active'] ? 'selected' : '' }}>{{ trans("admin-app.fields.yes") }}</option>
											<option value="0" {{ $data['buyer_premium_active'] ? '' : 'selected' }}>{{ trans("admin-app.fields.no") }}</option>
										</select>
									</td>
								</tr>
							@endif

							@if(isset($data['addComisionEmailBid']))
								<tr role="row" class="odd">
									<td style="width: 75%">{{ trans("admin-app.fields.percent_bid_comision") }}</td>
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
