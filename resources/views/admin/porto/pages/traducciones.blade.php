@extends('admin::layouts.logged')
@section('content')

    <section class="content-body" role="main">
        <header class="page-header">
            <h2></h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="javascript:;">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span> </span></li>
                </ol>

                <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
            </div>
        </header>

		@if (count(config('app.locales')) > 1)
			<div class="lang-changer">
				@php
					$langs = config('app.locales');
					unset($langs[mb_strtolower($data['lang'])]);
					$superadmin = !empty($_GET) && $_GET['admin'] == 'superadmin';
				@endphp
				@foreach ($langs as $lang => $lang_name)
					<a href="/admin/traducciones/{{$data['key']}}/{{mb_strtoupper($lang)}}{{ $superadmin ? '?admin=superadmin' : '' }}"
						class="mb-xs mt-xs mr-xs btn btn-sm btn-success">{{ trans("admin-app.button.change-to") }} {{ mb_strtoupper($lang) }}</a>
				@endforeach
			</div>
		@endif

        <div id="cms">
            <div class="row mb-10">
                <div class="col-md-12">
					<div class="d-flex align-items-center justify-content-end">
						<div class="d-flex align-items-center justify-content-end">
							<button class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_traducciones"
								type="button">{{ trans('admin-app.title.save') }}</button>
						</div>
					</div>
                </div>
            </div>
            <section class="panel">
                <form id="traducciones">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name='lang' type='hidden' value='{{ $data['lang'] }}'>
                    @if (!empty(head($data['original'])->key_header))
                        <input name='key_header' type='hidden' value='{{ head($data['original'])->key_header }}'>
                    @endif
                    <div class="panel-body">
                        <div class="dataTables_wrapper no-footer" id="datatable-default_wrapper">
                            <div class="">
                                <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer"
                                    id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                    <tbody>
                                        @foreach ($data[$data['lang']] as $key => $trad)
                                            @include('admin::includes.translations.translation', ['has_translation' => true])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            @if (!empty($_GET) && $_GET['admin'] == 'superadmin')
				@php
					$js_translate_template = view('admin::includes.translations.translation', ['has_translation' => false])->render();
				@endphp
                <form id='new_traduction'>
					<input type="hidden" name="translation_view" value='{{ $js_translate_template }}'>
					<input type="hidden" name="language_in_page" value="{{ $data['lang'] }}">
					<input type="hidden" name="key_header_in_page" value="{{ $data['key'] }}">
                    <section class="panel">
                        <div class="panel-body">
                            <div class="row mb-10">
                                <div class="col-md-3  mb-10">
                                    <select class="form-control" name='key_headers'>
                                        @foreach ($data['translateHeaders'] as $trans_header)
                                            <option value="{{ $trans_header->key_header }}" @selected($trans_header->key_header == $data['key'])>
                                                {{ $trans_header->key_header }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-9  mb-10">
                                    <input class="form-control" name='key_translate' type='text' placeholder="key">
                                </div>
                                <div class="col-md-3">
									<select class="form-control" name='lang'>
										@foreach (config('app.locales') as $iso_code => $locale)
											<option value="{{ mb_strtoupper($iso_code) }}" @selected(mb_strtoupper($iso_code) == $data['lang'])>{{ $locale }}</option>
										@endforeach
                                    </select>
                                </div>
                                <div class="col-md-9  mb-10">
                                    <input class="form-control" name='web_translation' type='text' placeholder="Texto">
                                </div>

                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <button class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right new_traducciones"
                                        type="button">{{ trans('admin-app.title.save') }}</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            @endif
        </div>

    </section>

    <script>
		document.addEventListener('keydown', function(event) {
			if (event.ctrlKey && event.key === 's') {
				event.preventDefault();
				$('.save_traducciones').click();
			}
		});
    </script>

@stop
