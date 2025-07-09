<div class="row mb-5">
    <div class="col-md-4">
        <h4>{{ trans('web.login_register.representar') }}</h4>
    </div>

    <div class="col-md-8">
        <label class="form-check-label">
            <input class="form-check-input" name="with-represented" type="checkbox" onchange="handleCheckedRepresented(this)" />
            Representar a otra persona persona física o jurídica
        </label>
        <div class="row mt-2 gy-1 d-none" id="js-representar">
			<input type="hidden" name="representar" value="N" />
            <div class="rsoc_table table-responsive col-12 pb-1">
                <table class="table table-bordered" id="js-repre-table">
                    <thead>
                        <tr>
                            <th>{{ trans("$theme-app.login_register.represented_alias") }}</th>
                            <th>{{ trans("$theme-app.login_register.represented_name") }}</th>
                            <th>{{ trans("$theme-app.login_register.represented_cif") }}</th>
                            <th>
								<button class="btn btn-sm" type="button" style="visibility: hidden;">
									<x-icon.boostrap icon="trash" />
								</button>
							</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input class="form-control effect-16" name="repre[0][alias]" type="text"
                                    onblur="comprueba_campo(this)" placeholder="Alias" autocomplete="off">
                            </td>
                            <td>
                                <input class="form-control effect-16" name="repre[0][name]" type="text"
                                    onblur="comprueba_campo(this)"
                                    placeholder="{{ trans("$theme-app.login_register.nombre") }}" autocomplete="off">
                            </td>
                            <td>
                                <input class="form-control effect-16" name="repre[0][cif]" type="text"
                                    onblur="comprueba_campo(this)" placeholder="C.I.F" autocomplete="off">
                            </td>
							<td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr id="rowButton">
                            <td class="text-center" colspan="4">
                                <button class="btn btn-lb-primary btn-sm" type="button" onclick="addRow()">
									<x-icon.boostrap icon="plus" />
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

				{{-- icon from copy --}}
				<div class="d-none" id="js-repre-delete-icon">
					<x-icon.boostrap icon="trash" />
				</div>


            </div>
        </div>
    </div>
</div>
