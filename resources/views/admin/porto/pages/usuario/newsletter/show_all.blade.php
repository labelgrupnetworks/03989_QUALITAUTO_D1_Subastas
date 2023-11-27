@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')

        <div class="row well header-well">
            <div class="col-xs-12">
                <h1 class="m-none">Newsletters</h1>
                <p class="text-right">
                    <a href="{{ route('newsletter.index') }}"
                        class="btn btn-primary right">{{ trans('admin-app.button.return') }}</a>
                </p>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 table-responsive">
                <table id="clients_newsletter" class="table table-striped table-condensed" style="width:100%"
                    data-order-name="order">

                    <thead>
                        <tr>

                            @foreach (array_keys($filters) as $head)
                                <th class="{{ $head }}" style="cursor: pointer;" data-order="{{ $head }}">
                                    {{ trans("admin-app.fields.$head") }}
                                    @if (request()->order == $head)
                                        <span style="margin-left: 5px; float: right;">
                                            @if (request()->order_dir == 'asc')
                                                <i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
                                            @endif
                                        </span>
                                    @endif
                                </th>
                            @endforeach

							<th class="">Suscripciones</th>

                            <th style="min-width: 160px">
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr id="filters">
                            <form class="form-group" action="">
                                <input type="hidden" name="order"
                                    value="{{ request('order', 'id_newsletter_suscription') }}">
                                <input type="hidden" name="order_dir" value="{{ request('order_dir', 'desc') }}">

                                @foreach ($filters as $param => $form)
                                    <td class="{{ $param }}"> {!! $form !!}</td>
                                @endforeach

								<td></td>

                                <td class="align-items-center d-flex gap-5 justify-content-space-between">
                                    <input type="submit" class="btn btn-sm btn-info"
                                        value="{{ trans('admin-app.button.search') }}">
                                    <a href="{{ url()->current() }}"
                                        class="btn btn-sm btn-warning">{{ trans('admin-app.button.restart') }}</a>
                                </td>
                            </form>
                        </tr>

                        @forelse ($suscriptions as $suscription)

                            <tr id="fila_{{ $suscription->id_newsletter_suscription }}">

                                @foreach (array_keys($filters) as $param)
                                    <td>{{ $suscription->{$param} }}</td>
                                @endforeach

								@php
									$newsletterSuscriptions = explode(',', $suscription->suscriptions);
								@endphp
								<td>
									<div class="" style="display: flex; flex-wrap: wrap; gap: 3px;">
									@foreach ($newsletterSuscriptions as $newsletterSuscription)
										<span class="label label-primary">
											{{ $newsletterSuscription }}
										</span>
									@endforeach
									</div>
								</td>

                                <td class="align-items-center d-flex gap-5 justify-content-space-between">

                                    @if (!empty($suscription->cod_cli))
                                        <a class="btn btn-info btn-xs"
                                            href="{{ route('clientes.edit', ['cliente' => $suscription->cod_cli]) }}">
                                            <i class="fa fa-eye"></i>
                                            Show
                                        </a>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            <div class="col-xs-12 d-flex justify-content-center">
                {{ $suscriptions->appends(array_except(Request::query(), ['page']))->links() }}
            </div>

        </div>

    </section>
@stop
