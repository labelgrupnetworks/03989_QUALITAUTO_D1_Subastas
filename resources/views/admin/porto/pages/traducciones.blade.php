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

        <div id="cms">
            <div class="row mb-10">
                <div class="col-md-12">
					<div class="d-flex align-items-center justify-content-space-between">
						@if (count(config('app.locales')) > 1)
							<div class="d-flex align-items-center justify-content-start">
								@php
									$langs = config('app.locales');
									unset($langs[mb_strtolower($data['lang'])]);
								@endphp
								@foreach ($langs as $lang => $lang_name)
									<a href="/admin/traducciones/{{$data['key']}}/{{mb_strtoupper($lang)}}"
										class="mb-xs mt-xs mr-xs btn btn-sm btn-success">{{ trans("admin-app.button.change-to") }} {{ mb_strtoupper($lang) }}</a>
								@endforeach
							</div>
						@endif
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
                                            <tr class="odd" role="row">
                                                <td style="width: 100%; ">
                                                    <div class="d-flex align-items-center">

														<div class="trans-title">
                                                            @if (!empty($data['original'][$key]->web_translation))
                                                                <span>{{ $data['original'][$key]->key_translate }}</span> -
                                                                <span
                                                                    style='font-size: 14px;    word-break: break-all;'>{{ $data['original'][$key]->web_translation }}</span>
                                                            @else
                                                                <span>{{ $data[$data['lang']][$key]->key_translate }}</span>
                                                                - <span
                                                                    style='font-size: 14px;    word-break: break-all;'>{{ $data[$data['lang']][$key]->web_translation }}</span>
                                                            @endif
                                                        </div>

                                                        <div class="d-flex align-items-center" style="margin-left: auto">
                                                            @php
                                                                $keySection = $data['key'];
                                                                $key =
                                                                    $data['original'][$key]->key_translate ??
                                                                    $data[$data['lang']][$key]->key_translate;
                                                                $textToPhp = "trans(\"\$theme-app.{$keySection}.{$key}\")";
                                                                $textToBlade = "{{ $textToPhp }}";
                                                            @endphp
                                                            <button class="copy-link btn btn-link p-0" type="button">
                                                                <svg height="24" viewBox="0 -0.114 49.742 51.317"
                                                                    width="24" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M49.626 11.564a.8.8 0 0 1 .028.209v10.972a.8.8 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.8.8 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.8.8 0 0 1 0 39.25V6.334q0-.108.028-.21c.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071q.033-.05.071-.093c.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.8.8 0 0 1 .8 0l9.61 5.533h.002q.046.032.088.068c.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.8.8 0 0 1 .028.209v20.559l8.008-4.611v-10.51q0-.106.028-.208c.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.8.8 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068m-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09V12.33L4.965 9.654zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764 4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61zm-.801 10.605-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833z"
                                                                        fill="#ff2d20" />
                                                                </svg>
                                                            </button>
                                                            <input type="text" value="{{ $textToBlade }}"
                                                                style="opacity: 0; width: 15px">

                                                            <button class="copy-link btn btn-link p-0" type="button">
                                                                <svg width="32" viewBox="0 0 256 134" style="height: auto"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    preserveAspectRatio="xMinYMin meet">
                                                                    <g fill-rule="evenodd">
                                                                        <ellipse fill="#8993BE" cx="128"
                                                                            cy="66.63" rx="128" ry="66.63" />
                                                                        <path
                                                                            d="m35.945 106.082 14.028-71.014H82.41c14.027.877 21.041 7.89 21.041 20.165 0 21.041-16.657 33.315-31.562 32.438H56.11l-3.507 18.411zm23.671-31.561L64 48.219h11.397c6.137 0 10.52 2.63 10.52 7.89-.876 14.905-7.89 17.535-15.78 18.412h-10.52zm40.576 13.15 14.027-71.013h16.658l-3.507 18.41h15.78c14.028.877 19.288 7.89 17.535 16.658l-6.137 35.945h-17.534l6.137-32.438c.876-4.384.876-7.014-5.26-7.014H124.74l-7.89 39.452zm53.233 18.411 14.027-71.014h32.438c14.028.877 21.042 7.89 21.042 20.165 0 21.041-16.658 33.315-31.562 32.438h-15.781l-3.507 18.411zm23.67-31.561 4.384-26.302h11.398c6.137 0 10.52 2.63 10.52 7.89-.876 14.905-7.89 17.535-15.78 18.412h-10.521z"
                                                                            fill="#232531" />
                                                                    </g>
                                                                </svg>
                                                            </button>
                                                            <input type="text" value="{{ $textToPhp }}"
                                                                style="opacity: 0; width: 5px">


                                                        </div>
                                                    </div>

                                                    @if (!empty($data[$data['lang']][$key]->id_key_translate) && $data[$data['lang']][$key]->id_key_translate !== null)
                                                        <input class="form-control"
                                                            name="{{ head($data['original'])->key_header }}**{{ $data[$data['lang']][$key]->key_translate }}"
                                                            type="text" value="{{ $trad->web_translation }}">
                                                    @else
                                                        <input class="form-control"
                                                            name="{{ head($data['original'])->key_header }}**{{ $data[$data['lang']][$key]->key_translate }}"
                                                            type="text" value="">
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            @php
				$traducciones = new \App\Models\Translate();
				$trans_headers = $traducciones->headersTrans();
			@endphp
            @if (!empty($_GET) && $_GET['admin'] == 'superadmin')
                <form id='new_traduction'>
                    <section class="panel">
                        <div class="panel-body">
                            <div class="row mb-10">
                                <div class="col-md-3  mb-10">
                                    <select class="form-control" name='key_headers'>
                                        @foreach ($trans_headers as $trans_header)
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
        $('.copy-link').on('click', copyToClipboard)

        function copyToClipboard(event) {
            const button = event.currentTarget;

            const input = button.nextElementSibling;
            input.select();
            document.execCommand('copy');

            notify('', 'Se ha copiado: \n' + input.value, 'success');
        }

		document.addEventListener('keydown', function(event) {
			if (event.ctrlKey && event.key === 's') {
				event.preventDefault();
				$('.save_traducciones').click();
			}
		});
    </script>

@stop
