@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')

        <div class="row well header-well">
            <div class="col-xs-12">
                <h1 class="m-none">Newsletters - {{ $newsletterName }}</h1>
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

                                <td class="align-items-center d-flex gap-5 justify-content-space-between">

                                    @if (!empty($suscription->cod_cli) && in_array('clientes', config('app.config_menu_admin')))
                                        <a class="btn btn-info btn-xs"
                                            href="{{ route('clientes.edit', ['cliente' => $suscription->cod_cli]) }}">
                                            <i class="fa fa-eye"></i>
                                            Show
                                        </a>
                                    @endif

                                    <button class="btn btn-danger btn-xs ml-auto" data-toggle="modal" data-target="#deleteModal"
                                        data-email="{{ $suscription->email_newsletter_suscription }}"
                                        data-url="{{ $suscription->url_unsuscribe }}" data-id="{{ $newsletterId }}"
                                        data-newsletter="{{ $newsletterName }}"
                                        data-row="{{ $suscription->id_newsletter_suscription }}">
                                        <i class="fa fa-trash"></i>
                                        Unsuscribe
                                        {{-- <tr id="fila_{{ $suscription->id_newsletter_suscription }}"> --}}
                                    </button>
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

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>{{ trans('admin-app.questions.delete_newsletter') }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('admin-app.button.close') }}</button>

                    <form id="formDelete" action="" data-row="" style="display: inline-block">
                        @csrf

                        <button type="submit" class="btn btn-danger">{{ trans('admin-app.button.delete') }}</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {

            $('#deleteModal').on('show.bs.modal', function(event) {

                const button = event.relatedTarget;
                const email = button.dataset.email;
                const newsletterId = button.dataset.id;
                const newwlstterName = button.dataset.newsletter;
                const row = button.dataset.row;

                const url = new URL(button.dataset.url);

                url.searchParams.append('type', 'json');
                url.searchParams.append('id', newsletterId);

                console.log(url);


                //obtenemos el id del data action del form
                //var action = $('#formDelete').attr('data-action').slice(0, -1) + id;

                //Le asignamos el nuevo id
                $('#formDelete').attr('action', url);
                $('#formDelete').attr('data-row', row);

                var modal = this;
                modal.querySelector('.modal-title').innerText =
                    `Vas a eliminar la subscripción de ${email} en la newsletter ${newwlstterName}`;
            });

        });

        $('#formDelete').on('submit', (event) => {
            event.preventDefault();

            const form = event.target;

            $.ajax({
                type: "GET",
                url: form.action,
                success: (response) => {
                    $(`#fila_${form.dataset.row}`).empty();
                    $('#deleteModal').modal('hide');
                    console.log(response);
                },
                error: (error) => {
                    console.log(error);
                }
            });
        })
    </script>

@stop
