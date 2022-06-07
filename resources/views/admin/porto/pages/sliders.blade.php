@extends('admin::layouts.logged')
@section('content')

	<section role="main" class="content-body">
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

		<div id="sliders">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">

					<div class="tabs tabs-primary">
						<ul class="nav nav-tabs">
							<li @if (empty($tab) || $tab == 'main')class="active" @endif>
								<a href="#new" data-toggle="tab"> {{ trans(\Config::get('app.theme').'-admin.slider.add_new') }} </a>
							</li>
							<li @if (!empty($tab) && $tab == 'settings')class="active" @endif>
								<a href="#settings" data-toggle="tab"> {{ trans(\Config::get('app.theme').'-admin.slider.settings') }} </a>
							</li>
						</ul>
						<div class="tab-content no-paddings">
							<div id="new" class="tab-pane @if (empty($tab) || $tab == 'main')active @endif">
								<section class="panel no-margins">
									<div class="panel-body" id="data-loader" data-loading-overlay data-loading-overlay-options='{ "startShowing": false }'>
										<div class="mb-md col-sm-12 no-paddings">
											<div class="col-sm-6">
												<form action="/admin/sliders/upload" class="dropzone dz-square" id="sliderupload">
													<div class="dz-message">
														{{ trans(\Config::get('app.theme').'-admin.slider.add_img') }}
														<i class="fa fa-hand-o-up"></i>
													</div>
												</form>
											</div>
											<div class="col-sm-6 img_place">
												<i class="fa fa-file-image-o"></i>
											</div>
										</div>
										<div class="p-md col-sm-12">
											<form action="/admin/sliders/save" id="main_slider_form" method="post" class="form-horizontal form-bordered">
												<div class="col-sm-6">

													<div class="form-group">
														<label for="inputDefault" class="col-md-3 control-label">{{ trans(\Config::get('app.theme').'-admin.slider.name') }}</label>
														<div class="col-md-6">
															<input type="text" name="name" class="form-control">
															<input type="hidden" name="file_url" />
															<input type="hidden" name="save" value="new" />
														</div>
													</div>
												</div>

												<div class="col-sm-6">
													<div class="form-group">
														<label for="inputDefault" class="col-md-3 control-label">{{ trans(\Config::get('app.theme').'-admin.slider.position') }}</label>
														<div class="col-md-6">
															<input type="number" min="1" step="1" name="position" pattern="^[0-9]" class="form-control">
														</div>
													</div>
												</div>

												<div class="p-md col-sm-12">
													<div class="form-group">
                                                                                                            <textarea name="summernote" class="summernote" data-plugin-summernote data-plugin-options='{ "height": 180, "codemirror": { "theme": "ambiance" } }'></textarea>
													</div>

													<div class="actions">
														<a href="/admin/sliders" class="btn hvr-shutter-out-horizontal red" type="submit"><i class="fa fa-refresh"></i> {{ trans(\Config::get('app.theme').'-admin.slider.restart') }}</a>
														<button class="btn hvr-shutter-out-horizontal submit save-slider" type="button"><i class="fa fa-save"></i> {{ trans(\Config::get('app.theme').'-admin.slider.save') }}</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</section>
							</div>
							<div id="settings" class="tab-pane @if (!empty($tab) && $tab == 'settings')active @endif">
								<div class="panel-body">
									<div class="col-sm-4">
										<div class="tab_title">
											<p>{{ trans(\Config::get('app.theme').'-admin.slider.order') }}</p>
											<hr >
										</div>
										<div data-plugin-portlet id="order">
											<section class="panel panel-primary" id="slider_1" data-portlet-item>
												<div class="alert alert-primary portlet-handler">
													Nombre slider 1
												</div>
											</section>
											<section class="panel panel-primary" id="slider_2" data-portlet-item>
												<div class="alert alert-primary portlet-handler">
													Nombre slider 2
												</div>
											</section>
											<section class="panel panel-primary" id="slider_3" data-portlet-item>
												<div class="alert alert-primary portlet-handler">
													Nombre slider 3
												</div>
											</section>
										</div>
									</div>
									<form action="/admin/sliders/save" id="main_slider_form" method="post" class="form-horizontal form-bordered">
									<input type="hidden" name="save" value="settings" />
										<div class="actions">
											<a href="/admin/sliders/settings" class="btn hvr-shutter-out-horizontal red" type="submit"><i class="fa fa-refresh"></i> {{ trans(\Config::get('app.theme').'-admin.slider.restart') }}</a>
											<button class="btn hvr-shutter-out-horizontal submit" type="button"><i class="fa fa-save"></i> {{ trans(\Config::get('app.theme').'-admin.slider.save') }}</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

			
		</div>

		<div id="thumbnails" class="hidden"></div>
	</section>
		
@stop