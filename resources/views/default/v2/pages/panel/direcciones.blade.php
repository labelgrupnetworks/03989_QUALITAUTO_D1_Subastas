@extends('layouts.default')

@section('content')
    <main class="container user-panel-page account-address-page">

        <div class="row">

            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">

                <h1>{{ trans("$theme-app.user_panel.addresses") }}</h1>
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

                                    <button class="btn btn-sm btn-lb-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editAddress" data-codd="{{ $address->codd_clid }}"
                                        type="button" title="{{ trans("$theme-app.user_panel.edit") }}">{{ trans("$theme-app.user_panel.edit") }}</button>

                                    @if ($address->codd_clid != 'W1')
                                        <button class="btn btn-sm btn-lb-secondary fav-address" title="{{ trans("$theme-app.user_panel.select_as_parent") }}"
                                            cod="{{ $address->codd_clid }}">
											@include('components.boostrap_icon', ['icon' => 'star', 'size' => '18'])
										</button>

										<button class="btn btn-sm btn-lb-secondary delete-address" cod="{{ $address->codd_clid }}" title="{{ trans("$theme-app.user_panel.remove") }}">
											@include('components.boostrap_icon', ['icon' => 'trash', 'size' => '18'])
										</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col">

                        <button class="card address-card h-100 w-100" data-bs-toggle="modal" data-bs-target="#editAddress" data-codd="0">
                            <div class="card-body card-new-direction">
								@include('components.boostrap_icon', ['icon' => 'plus', 'size' => '48'])
                                {{ trans("$theme-app.user_panel.add_new_address") }}
                            </div>
                        </button>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <div class="modal fade" id="editAddress" aria-labelledby="editAddressLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressLabel">{{ trans("$theme-app.user_panel.edit") }} {{ trans("$theme-app.user_panel.address") }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="save_address_shipping" id="save_address_shipping">
                        @csrf
                        <input class="codd_clid" name="codd_clid" type="hidden" value="">
                        <div class="row gy-1">

                            <div class="col-md-6">
                                <label for="nombre">{{ trans("$theme-app.login_register.nombre_apellido") }}</label>
                                <input class="form-control" id="nombre" name="usuario" type="text" required>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono">{{ trans("$theme-app.user_panel.phone") }}</label>
                                <input class="form-control" id="telefono" name="telefono" type="text" required
                                    maxlength="40">
                            </div>
                            <div class="col-md-2">
                                <label for="codigoVia">{{ trans("$theme-app.login_register.via") }}</label>
                                <select class="form-select" id="codigoVia" name="clid_codigoVia">
                                    @foreach ($data['via'] as $cod_sg => $des_sg)
                                        <option value="{{ $cod_sg }}">{{ $des_sg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-10">
                                <label for="direccion">{{ trans("$theme-app.user_panel.address") }}</label>
                                <input class="form-control dreccion_cambiar" id="direccion" name="clid_direccion"
                                    type="text" maxlength="60" required>
                            </div>

                            <div class="col-md-6">
                                <label for="country_envio">{{ trans("$theme-app.user_panel.pais") }}</label>
                                <select class="form-select" id="country_envio" name="clid_pais" required>
                                    @foreach ($data['countries'] as $cod_paises => $des_paises)
                                        <option value="{{ $cod_paises }}">{{ $des_paises }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_postal">{{ trans("$theme-app.user_panel.zip_code") }}</label>
                                <input class="form-control" id="codigo_postal" name="clid_cpostal" type="text" required
                                    maxlength="10">
                            </div>
                            <div class="col-md-6">
                                <label for="clid_provincia">{{ trans("$theme-app.login_register.provincia") }}</label>
                                <input class="form-control" id="clid_provincia" name="clid_provincia" type="text"
                                    maxlength="30">
                            </div>
                            <div class="col-md-6">
                                <label for="clid_poblacion">{{ trans("$theme-app.user_panel.city") }}</label>
                                <input class="form-control" id="clid_poblacion" name="clid_poblacion" type="text"
                                    required maxlength="30">
                            </div>
                        </div>
                    </form>
                    <input id="lang_dirreciones" type="hidden" value="{{ config('app.locale') }}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-lb-primary" data-bs-dismiss="modal" type="button">{{ trans("$theme-app.head.close") }}</button>
                    <button class="btn btn-lb-primary" form="save_address_shipping" type="submit">{{ trans("$theme-app.user_panel.save") }}</button>
                </div>
            </div>
        </div>
    </div>


    @if (Config::get('app.delivery_address'))
        <div class="container modal-block mfp-hide" id="modalDeletAddress" data-to="modalDeletAddress">
            <div class="" data-to="modalDeletAddress">
                <section class="panel">
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class=" text-center single_item_content_">
                                <p>{{ trans("$theme-app.user_panel.delete_address") }}</p><br />
                                <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}" />
                                <input id="cod_delete" name="cod" type="hidden" value="">
                                <input id="lang" name="lang" type="hidden"
                                    value="<?= Config::get('app.locale') ?>">

                            </div>
							<div class="modal-footer">
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
        const newAddress = document.getElementById('newAddress');

        editAddress.addEventListener('show.bs.modal', (event) => {

            const buttoElement = event.relatedTarget;
            const codd = buttoElement.dataset.codd;

            const address = addresses.find((address) => address.codd_clid === codd);
            console.log(address);

            editAddress.querySelector('[name=codd_clid]').value = address?.codd_clid || "";
            editAddress.querySelector('[name=usuario]').value = address?.nomd_clid || "";
            editAddress.querySelector('[name=telefono]').value = address?.tel1_clid || "";
            editAddress.querySelector('[name=clid_codigoVia]').value = address?.sg_clid || "";
            editAddress.querySelector('[name=clid_direccion]').value = address.dir_clid ? `${address.dir_clid ?? ''}${address.dir2_clid ?? ''}` : "";
            editAddress.querySelector('[name=clid_pais]').value = address?.codpais_clid || "";
            editAddress.querySelector('[name=clid_cpostal]').value = address?.cp_clid || "";
            editAddress.querySelector('[name=clid_provincia]').value = address?.pro_clid || "";
            editAddress.querySelector('[name=clid_poblacion]').value = address?.pob_clid || "";
        });

        $(".save_address_shipping").submit(function(event) {
            submit_shipping_addres(event, this).then(result => document.location.reload());
        });

        $(".fav-address").click(function() {
            fav_addres(this).then(result => document.location.reload());
        });

		$(".delete-address").click(function(){
              delete_shipping_addres(this);
        });
    </script>
@stop
