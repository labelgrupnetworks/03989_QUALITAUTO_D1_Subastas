@extends('admin::layouts.logged')
@section('content')

    <section role="main" class="content-body">
        @include('admin::includes.header_content')

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-none">{{ trans('admin-app.title.clients_newsletter') }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="col-xs-12 text-right mb-1 pt-1 pb-1" style="background-color: #ffe7e7">
				<div class="btn-group">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					  {{ trans("admin-app.button.export") }} <span class="caret"></span>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
					  <li><a href="{{ route('newsletter.export', ['format' => 'xlsx']) }}">{{ trans("admin-app.button.file_excel") }}</a></li>
					  <li><a href="{{ route('newsletter.export', ['format' => 'csv']) }}">{{ trans("admin-app.button.file_csv") }}</a></li>
					  {{-- <li><a href="{{ route('newsletter.export', ['format' => 'csv', 'service' => 'mailchimp']) }}">Formato para Mailchimp</a></li> --}}
					</ul>
				  </div>
            </div>

            <div class="col-xs-12 table-responsive">
                <table id="newsletter" class="table table-striped table-condensed" style="width:100%">

                    <thead>
                        <tr>

                            <th>{{ trans("admin-app.fields.id") }}</th>
                            <th>{{ trans("admin-app.fields.name") }}</th>
                            <th>{{ trans("admin-app.fields.suscribers") }}</th>

                            <th>
                                <span>{{ trans('admin-app.fields.actions') }}</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>

						<tr id="fila_all">

							<td>{{ trans("admin-app.fields.numeral_sign") }}</td>
							<td>{{ trans("admin-app.fields.all") }}</td>
							<td>{{ $allSuscriptors }}</td>

							<td>
								<a class="btn btn-xs btn-default"
									href="{{ route('user_newsletter.show', [0]) }}"><i
										class="fa fa-list"></i> {{ trans("admin-app.button.see_suscriptors") }}</a>

							</td>
						</tr>

                        @forelse ($newsletters as $newsletter)
                            <tr id="fila_{{ $newsletter->id }}">

                                <td>{{ $newsletter->id_newsletter }}</td>
                                <td>{{ $newsletter->name_newsletter }}</td>
                                <td>{{ $newsletter->suscriptors_count }}</td>

                                <td>
                                    <button class="btn btn-xs btn-default"
                                        onclick="editNewsletter('{{ route('newsletter.edit', ['newsletter' => $newsletter->id]) }}')">
                                        <i class="fa fa-edit"></i> {{ trans("admin-app.button.edit") }}</button>

                                    <a class="btn btn-xs btn-default"
                                        href="{{ route('user_newsletter.show', [$newsletter->id_newsletter]) }}"><i
                                            class="fa fa-list"></i> {{ trans("admin-app.button.see_suscriptors") }}</a>

                                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#deleteModal"
										data-id="{{ $newsletter->id_newsletter }}" data-name="{{ $newsletter->name_newsletter }}">
                                        <i class="fa fa-trash-o"></i>
										{{ trans("admin-app.button.destroy") }}
									</button>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">
                                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                                </td>
                            </tr>
                        @endforelse

						@if($catalogsCount)
						<tr id="fila_catalogs">

							<td>{{ trans("admin-app.fields.numeral_sign") }}</td>
							<td>{{ trans("admin-app.fields.catalogue") }}</td>
							<td>{{ $catalogsCount }}</td>

							<td>
								<a class="btn btn-xs btn-default"
									href=""><i
										class="fa fa-list"></i> {{ trans("admin-app.button.see_suscriptors") }}</a>

							</td>
						</tr>
						@endif

                    </tbody>

                </table>

                <button class="btn btn-sm btn-success" onclick="createNewsletter()">
                    <i class="fa fa-plus"></i>
                    {{ trans("admin-app.button.add") }}
                </button>
            </div>

        </div>

    </section>

    <div class="modal fade" id="newsletterModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="newsletter-form" class="d-flex flex-column" style="gap: 3rem;" method="POST"
                        data-action-update="{{ route('newsletter.update', ['newsletter' => 0]) }}"
                        data-action-store="{{ route('newsletter.store') }}">
                        <input type="hidden" name="_method" value="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name_newsletter" class="control-label">{{ trans("admin-app.title.nombre") }}:</label>
                            <input name="newsletter[{{ mb_strtoupper(config('app.locale')) }}]" type="text"
                                class="form-control" id="name_newsletter"
                                data-lang="{{ mb_strtoupper(config('app.locale')) }}">
                        </div>

                        <fieldset>
                            <legend class="scheduler-border">{{ trans("admin-app.title.translates") }}</legend>
                            @if (isMultilanguage())
                                @foreach (Tools::getOtherLanguages() as $key => $locale)
                                    <div class="form-group">
                                        <label for="newsletter_{{ $key }}"
                                            class="control-label">{{ $locale }}:</label>
                                        <input name="newsletter[{{ mb_strtoupper($key) }}]" type="text"
                                            class="form-control" id="newsletter_{{ $key }}"
                                            data-lang="{{ mb_strtoupper($key) }}">
                                    </div>
                                @endforeach
                            @endif
                        </fieldset>

                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ trans('admin-app.button.close') }}</button>

                    <button form="newsletter-form" type="submit"
                        class="btn btn-success">{{ trans('admin-app.button.save') }}</button>
                </div>

            </div>
        </div>
    </div>

    @include('admin::includes._delete_modal', ['routeToDelete' => route('newsletter.destroy', ['newsletter' => 0])]);

    <script>
        function editNewsletter(url) {
            fetch(url)
                .then((response) => response.json())
                .then(showModalNewsletter);
        }

        function showModalNewsletter({
            newsletter
        }) {
            const {
                id,
                id_newsletter,
                name_newsletter,
                lang_newsletter,
                languages
            } = newsletter;

            const modal = document.getElementById('newsletterModal');
            const form = modal.querySelector('form');

            const action = form.dataset.actionUpdate.slice(0, -1) + id;

            form.action = action;
            form.querySelector('[name="_method"]').value = 'PUT';

            const addElementValues = (element) => {
                const {
                    lang
                } = element.dataset;

                element.value = (lang === lang_newsletter) ?
                    name_newsletter :
                    languages
                    .find((newsletter) => newsletter.lang_newsletter === lang)?.name_newsletter || '';
            }

            modal.querySelectorAll(`[name^="newsletter"`).forEach(addElementValues);
            $(modal).modal('show');
        }

        function createNewsletter() {
            const modal = document.getElementById('newsletterModal');
            const form = modal.querySelector('form');

            const action = form.dataset.actionStore;
            form.action = action;
            form.querySelector('[name="_method"]').value = 'POST';

            cleanInputs = (element) => element.value = '';

            form.querySelectorAll(`[name^="newsletter"`).forEach(cleanInputs);

            $(modal).modal('show');
        }

        function destroyNewsletter(event) {

            const button = $(event.relatedTarget);
            const id = button.data('id');
			const name = button.data('name');
            const action = $('#formDelete').attr('data-action').slice(0, -1) + id;

            //Le asignamos el nuevo id
            $('#formDelete').attr('action', action);

            var modal = $(this);
            modal.find('.modal-title').text('Vas a eliminar la newsletter ' + name);
        }

        $(() => {
            $('#deleteModal').on('show.bs.modal', destroyNewsletter)
        })
    </script>
@stop
