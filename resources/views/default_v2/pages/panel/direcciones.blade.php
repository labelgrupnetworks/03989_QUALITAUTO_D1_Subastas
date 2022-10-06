@extends('layouts.default')

@section('content')
    <section class="container user-panel-page account-address-page">

        <div class="row">

            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">

                <h1>Direcciones</h1>
                <section class="adrresses row row-cols-1 row-cols-md-2 row-cols-lg-3 my-3 gy-2">
                    @foreach ($data['shippingaddress'] as $key => $address)
                        <div class="col">
                            <div class="card h-100 address-card">
                                <div class="card-body">
                                    <p class="fw-bold">
                                        {{ $address->nomd_clid }}
                                    </p>
                                    <p>{{ "$address->sg_clid $address->dir_clid $address->dir2_clid" }}</p>
                                    <p>{{ $address->pro_clid }}</p>
                                    <p>{{ "$address->pob_clid $address->cp_clid" }}</p>
                                    <p>{{ $data['countries'][$address->codpais_clid] }}</p>
                                </div>
                                <div class="card-footer d-flex flex-wrap gap-2">

                                    <button type="button" class="btn btn-sm btn-lb-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editAddress" data-codd="{{ $address->codd_clid }}">Editar</button>
                                    @if ($address->codd_clid != 'W1')
                                        <button class="btn btn-sm btn-lb-secondary">Seleccionar como principal</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col">
                        <button class="card address-card h-100 w-100">
                            <div class="card-body card-new-direction">
                                <svg class="bi" width="48" height="48" fill="currentColor">
                                    <use xlink:href="/bootstrap-icons.svg#plus"></use>
                                </svg>
                                Añadir nueva dirección
                            </div>
                        </button>
                    </div>
                </section>

            </div>
        </div>
    </section>

    <div class="modal fade" id="editAddress" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressLabel">Editar dirección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="save_address_shipping">
                        @csrf
                        <input class="codd_clid" type="hidden" name="codd_clid" value="">
                        <div class="row gy-1">

                            <div class="col-md-6">
                                <label for="nombre">{{ trans("$theme-app.login_register.nombre_apellido") }}</label>
                                <input id="nombre" type="text" class="form-control" name="usuario" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono">{{ trans("$theme-app.user_panel.phone") }}</label>
                                <input id="telefono" type="text" name="telefono" class="form-control" required maxlength="40">
                            </div>
                            <div class="col-md-2">
                                <label for="codigoVia">{{ trans("$theme-app.login_register.via") }}</label>
                                <select id="codigoVia" name="clid_codigoVia" class="form-select">
                                    @foreach ($data['via'] as $cod_sg => $des_sg)
                                        <option value="{{ $cod_sg }}">{{ $des_sg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-10">
                                <label for="direccion">{{ trans("$theme-app.user_panel.address") }}</label>
                                <input id="direccion" type="text" name="clid_direccion" class="form-control dreccion_cambiar"
                                      maxlength="60" required>
                            </div>

                            <div class="col-md-6">
                                <label for="country_envio">{{ trans("$theme-app.user_panel.pais") }}</label>
                                <select id="country_envio" name="clid_pais" class="form-select" required>
                                    @foreach ($data['countries'] as $cod_paises => $des_paises)
                                        <option value="{{ $cod_paises }}">{{ $des_paises }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_postal">{{ trans("$theme-app.user_panel.zip_code") }}</label>
                                <input id="codigo_postal" type="text" name="clid_cpostal" class="form-control" required
                                    maxlength="10">
                            </div>
                            <div class="col-md-6">
                                <label for="clid_provincia">{{ trans("$theme-app.login_register.provincia") }}</label>
                                <input id="clid_provincia" name="clid_provincia" class="form-control" maxlength="30"
                                    type="text">
                            </div>
                            <div class="col-md-6">
                                <label for="clid_poblacion">{{ trans("$theme-app.user_panel.city") }}</label>
                                <input id="clid_poblacion" type="text" name="clid_poblacion" class="form-control"
                                    required maxlength="30">
                            </div>
                        </div>
                    </form>
                    <input type="hidden" id="lang_dirreciones" value="{{ config('app.locale') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>


    @if (Config::get('app.delivery_address'))
        <div id="modalDeletAddress" class="container modal-block mfp-hide" data-to="modalDeletAddress">
            <div data-to="modalDeletAddress" class="">
                <section class="panel">
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class=" text-center single_item_content_">
                                <p>{{ trans("$theme-app.user_panel.delete_address") }}</p><br />
                                <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
                                <input value="" name="cod" type="hidden" id="cod_delete">
                                <input value="<?= Config::get('app.locale') ?>" name="lang" type="hidden"
                                    id="lang">
                                <button
                                    class=" btn button_modal_confirm modal-dismiss modal-confirm">{{ trans("$theme-app.lot.accept") }}</button>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endif

    <script>
        const addresses = @json($data['shippingaddress']);
        const editAddress = document.getElementById('editAddress');
        editAddress.addEventListener('show.bs.modal', (event) => {

            const buttoElement = event.relatedTarget;
            const codd = buttoElement.dataset.codd;
            const address = addresses.find((address) => address.codd_clid === codd);
            console.log(address);

            editAddress.querySelector('[name=codd_clid]').value = address.codd;
            editAddress.querySelector('[name=usuario]').value = address.nomd_clid;
            editAddress.querySelector('[name=telefono]').value = address.tel1_clid;
            editAddress.querySelector('[name=clid_codigoVia]').value = address.sg_clid;
            editAddress.querySelector('[name=clid_direccion]').value = `${address.dir_clid ?? ''}${address.dir2_clid ?? ''}`;
            editAddress.querySelector('[name=clid_pais]').value = address.codpais_clid;
            editAddress.querySelector('[name=clid_cpostal]').value = address.cp_clid;
            editAddress.querySelector('[name=clid_provincia]').value = address.pro_clid;
            editAddress.querySelector('[name=clid_poblacion]').value = address.pob_clid;
        });
    </script>

@stop
