



<div id="cms">
	<div class="row">
		<div class="col-md-8">
			<div class="panel">
				<div class="panel-body">

					<fieldset>
						<legend>{{ trans("admin-app.title.propieties") }}</legend>
						<div class="row">
							<div class="col-md-5">

								<div class="form-group">
									<label>{{ trans("admin-app.title.front_page") }}</label>
									{!! FormLib::File('url_img', false) !!}
								</div>

								<div class="front-wrapper" style="border: 1px solid rgb(0,0,0,0.2)">
									@if(Tools::fileNameIsImage($noticiaLocale->img_web_blog))

									<img src="{{ $noticiaLocale->img_web_blog }}" class="img-responsive"
										style="max-height: 500px; margin: auto">

									@elseif(Tools::fileNameIsVideo($noticiaLocale->img_web_blog))

									<video src="{{ $noticiaLocale->img_web_blog }}" class="img-responsive"
										style="max-height: 500px; margin: auto" controls></video>
									@endif
								</div>

							</div>

							<div class="col-md-7">
								<div class="form-group">
									<label>
										<sup>*</sup>
										{{ trans('admin-app.placeholder.nombre') }}
										<i class="fa fa-info-circle" aria-hidden="true"
											data-toggle="tooltip" data-placement="right"
											data-original-title="{{ trans("admin-app.title.blog_name_save_page") }}">
										</i>
									</label>
									{!! FormLib::Text('title', true, $noticiaLocale->title_web_blog) !!}
								</div>

								<div class="form-group">
									<label>
										<sup>*</sup>
										{{ trans('admin-app.placeholder.fecha_publicacion') }}
										<i class="fa fa-info-circle" aria-hidden="true"
											data-toggle="tooltip" data-placement="right"
											data-original-title="{{ trans("admin-app.title.blog_show_page_public_data") }}">
										</i>
									</label>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input type="datetime" data-plugin-datepicker data-date-format="yyyy-mm-dd"
											class="form-control" name="date" autocomplete="off"
											value='{{ $noticiaLocale->publication_date_web_blog ? date('Y-m-d', strtotime($noticiaLocale->publication_date_web_blog)) : '' }}'>
									</div>
								</div>

								<div class="form-group">
									<label>{{ trans('admin-app.placeholder.author') }}</label>
									{!! FormLib::Text('author', false, $noticiaLocale->author_web_blog, 'maxlength="100"') !!}
								</div>

								<div class="form-group">
									<sup>*</sup>
									<label>{{ trans('admin-app.title.categories_blog_principal') }}</label>
									{!! FormLib::Select(
										'categ_blog_principal',
										true,
										$noticiaLocale->primary_category_web_blog,
										$data['categories_select'],
										'',
										'',
										false
									) !!}
								</div>

								<div class="form-group">
									<label>{{ trans("admin-app.fields.related_categories") }}</label>
									{!! FormLib::Select2WithArray(
										'categ_blog',
										false,
										$noticia['categories'] ?? null,
										$data['categories_select'],
										false,
										true
									) !!}
								</div>

								<div class="form-group">
									<label>{{ trans("admin-app.fields.section_type") }}
										<i class="fa fa-info-circle" aria-hidden="true"
											data-toggle="tooltip" data-placement="right"
											data-original-title="{{ trans("admin-app.title.blog_section_related_entry") }}">
										</i>
									</label>
									{!! FormLib::Select2WithArray('sec', false, $noticia['lot_categories_web_blog'] ?? null, $data['sec'], false, true) !!}
								</div>

								<div class="form-group">
									<label>{{ trans("admin-app.fields.section") }}
										<i class="fa fa-info-circle" aria-hidden="true"
											data-toggle="tooltip" data-placement="right"
											data-original-title="{{ trans("admin-app.title.blog_section_related_entry") }}">
										</i>
									</label>
									{!! FormLib::Select2WithArray(
										'sub_categ',
										false,
										$noticia['lot_sub_categories_web_blog'] ?? null,
										$data['sub_categ']->pluck('des_ortsec0', 'cod_sec'),
										false,
										true
									) !!}
								</div>

							</div>
						</div>

					</fieldset>
				</div>
			</div>
		</div>

		<div class="col-md-4">

			<div class="panel">
				<div class="panel-body">
					<div class="d-flex flex-direction-column"
						style="gap: 1rem">

						@if(Route::currentRouteName() == 'admin.contenido.blog.edit')
						<div class="d-flex" style="gap: 1rem">
							<button type="button" class="btn btn-block {{ $noticiaLocale->enabled_web_blog_lang ? 'btn-danger' : 'btn-success' }}"
								data-is-enabled="{{ $noticiaLocale->enabled_web_blog_lang == 1 ? 'true' : 'false' }}"
								data-enabled-message="{{ trans("admin-app.fields.activate") }}"
								data-disabled-message="{{ trans("admin-app.fields.deactivate") }}"
								onclick="handleClickChangeEnabledStatus(this)"
							>
							{{ $noticiaLocale->enabled_web_blog_lang ? trans("admin-app.fields.deactivate") : trans("admin-app.fields.activate") }}
							</button>

							@if($noticiaLocale->url_web_blog_lang && $noticiaLocale->publication_date_web_blog)
							<a href="{{ $noticiaLocale->link }}" target="_blank" class="btn btn-block btn-info">{{ trans("admin-app.button.preview") }}</a>
							@else
							<button type="button" class="btn btn-block btn-info" onclick="handleClickShowPage()">{{ trans("admin-app.button.preview") }}</button>
							@endif
						</div>
						@endif

						<button type="submit"
							class="btn btn-block btn-lg btn-primary">{{ trans('admin-app.title.save') }}</button>
					</div>

				</div>
			</div>

			{{-- seo --}}

			<div class="panel">
				<div class="panel-body">

					<fieldset>
						<legend>{{ mb_strtoupper(trans("admin-app.title.seo")) }}</legend>

						<div class="tabs tabs-bottom tabs-primary">
							<ul class="nav nav-tabs nav-justified">

								@foreach ($data['idiomes'] as $idiomes => $keylang)
									<li class="{{ $loop->first ? 'active' : '' }}">
										<a href="#{{ $idiomes }}" data-toggle="tab" class="text-center">
											{{ $keylang }}
										</a>
									</li>
								@endforeach
							</ul>

							<div class="tab-content">

								@foreach (array_keys($data['idiomes']) as $idiomes)
									<div id="{{ $idiomes }}"
										class="tab-pane {{ $loop->first ? 'active' : '' }}">

										<div class='row'>
											@php
												$blog_lang = new App\Models\V5\Web_Blog();
												$idiomes = strtoupper($idiomes);
												$maxLength = 'maxlength="255"';

												if (!empty($noticia['lang']) && !empty($noticia['lang'][$idiomes])) {
													$blog_lang = $noticia['lang'][$idiomes];
												}
											@endphp

											<div class="form-group">
												<sup>*</sup>
												<label>{{ trans('admin-app.placeholder.titulo') }}</label>
												{!! FormLib::Text("title_$idiomes", false, $blog_lang->titulo_web_blog_lang, $maxLength) !!}
											</div>


											<div class="form-group">
												<sup>*</sup>
												<label>{{ trans('admin-app.placeholder.url') }}</label>
												{!! FormLib::Text("url_$idiomes", false, $blog_lang->url_web_blog_lang, $maxLength) !!}
											</div>

											<div class="form-group">
												<label>{{ trans('admin-app.placeholder.cita') }} / descripción</label>
												{!! FormLib::Text("cita_$idiomes", false, $blog_lang->cita_web_blog_lang, $maxLength) !!}
											</div>

											<div class="form-group">
												<label>{{ trans('admin-app.placeholder.meta_title') }}</label>
												{!! FormLib::Text("meta_title_$idiomes", false, $blog_lang->metatitle_web_blog_lang, $maxLength) !!}
											</div>

											<div class="form-group">
												<label>{{ trans('admin-app.placeholder.meta_description') }}</label>
												{!! FormLib::Text("meta_desc_$idiomes", false, $blog_lang->metadescription_web_blog_lang) !!}
											</div>

											{{-- <div class="form-group">
												<label>{{ trans('admin-app.placeholder.video') }}</label>
												{!! FormLib::Text("video_$idiomes", false, $blog_lang->video_web_blog_lang, $maxLength) !!}
											</div> --}}

										</div>
									</div>
								@endforeach

							</div>
						</div>

					</fieldset>

				</div>
			</div>


		</div>
	</div>

</div>
