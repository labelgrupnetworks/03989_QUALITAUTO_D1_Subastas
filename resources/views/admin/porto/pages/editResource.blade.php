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

		<div id="resources">
			<div class="row">
                            <form id="edit_resources">
                             <div class="col-md-10">
                                <section class="panel">
                                    <?php
                                    if(!empty($_GET["see"]) && $_GET["see"] == 'A'){
                                        $see = 'article';
                                    }
                                    if(!empty($_GET["see"]) && $_GET["see"] == 'C'){
                                        $see = 'calendar';
                                    }
                                    ?>
                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-8">
                                                                    <p>{{ trans('admin-app.placeholder.nombre') }}</p>
                                                                         <input type="text" name="name" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="{{ empty($bloque->title)? '' : $bloque->title}}">
                                                                   <p style="margin-top: 10px; font-size: large;">ID: {{ empty($bloque->id_web_resource)? '' : $bloque->id_web_resource }}</p>
                                                                </div>
                                                                <div class=" hidden">
                                                                    <p>{{ trans('admin-app.placeholder.cache') }}</p>
                                                                    <input type="text" name="cache" placeholder="{{ trans('admin-app.placeholder.cache') }}" class="form-control" value="{{ empty($bloque->time_cache)? '' : $bloque->time_cache}}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <p>
                                                                    @if((!empty($bloque->type) && $bloque->type == 'A') || (!empty($see) && $see == 'article'))
                                                                        {{ trans('admin-app.selected.articulos') }}
                                                                    @elseif((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar'))
                                                                        {{ trans('admin-app.selected.imagen') }}
                                                                    @else
                                                                        {{ trans('admin-app.selected.htmlorimagen')}}
                                                                    @endif
                                                                    </p>
                                                                    <select class="form-control input-sm mb-md target" name="type">
                                                                    @if((!empty($bloque->type) && $bloque->type == 'A') || (!empty($see) && $see == 'article'))
                                                                        <option value="A">{{ trans('admin-app.selected.articulos') }}</option>
                                                                    @elseif((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar'))
                                                                        <option value="C">{{ trans('admin-app.selected.imagen') }}</option>
                                                                    @else
                                                                        <option <?= (empty($bloque->type) || $bloque->type == 'H')? 'selected' : ''; ?> value="H">{{ trans('admin-app.selected.html') }}</option>
                                                                        <option <?= (empty($bloque->type) || $bloque->type == 'I')? 'selected' : ''; ?> value="I" >{{ trans('admin-app.selected.imagen') }}</option>
                                                                    @endif
                                                                    </select>
                                                                </div>


                                                                <div class="mb-md hidden-lg hidden-xl"></div>
                                       
                                                                       
                                                        </div>
                                                        <div class="row form-group ">
                                                            <div class="col-md-6 imagen" style="display:<?= ((empty($see) && (empty($bloque->type) || $bloque->type == 'I')) || ((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar')))? 'block' : 'none'; ?>" >   
                                                                <p>{{ trans('admin-app.placeholder.url') }}</p>        
                                                                <input type="text" name="url_link" placeholder="{{ trans('admin-app.placeholder.url') }}" class="form-control" value="{{ empty($bloque)? '' : $bloque->url_link}}">
                        
                                                             </div>
                                                             <div class="col-md-3 imagen" style="display:<?= ((empty($see) && (empty($bloque->type) || $bloque->type == 'I')) || ((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar')))? 'block' : 'none'; ?> " >                   
                                                                 <p>&nbsp;</p>
                                                                 <div class="checkbox-custom checkbox-default">
                                                                    <input name="new_windows" type="checkbox" <?= (empty($see) && (empty($bloque->new_window) || $bloque->new_window == 0))? '' : 'checked' ; ?>  id="checkboxPage">
                                                                    <label for="checkboxPage">{{ trans('admin-app.selected.new_page') }}</label>
                                                                 </div>
                                                                 
                                                            </div>
                               
                                                            <div class="col-md-3">   
                                                                
                                                            </div>
                                                          
                                                            <div class="col-md-12 imagen mt-10" style="display:<?= (empty($see) && (empty($bloque->type) || $bloque->type == 'I'))? 'block' : 'none'; ?>">   
                                                                    <p>{{ trans('admin-app.placeholder.text') }}</p>        
                                                                    <input type="text" name="text_html" placeholder="{{ trans('admin-app.placeholder.text') }}" class="form-control" value="{{ empty($bloque->content)? '' : $bloque->content}}">

                                                            </div>
                                                            <div class="col-md-12 imagen mt-10" style="display:<?= ((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar'))? 'block' : 'none'; ?>;margin-top: 20px;">   
                                                                   <label for="datetimepicker">Fecha de subasta:</label>
                                                                    <div class="form-group" style="display: inline-table;">
                                                                        <input name="fecha" class="form-control" type="date" required="" id="datetimepicker" value="{{ empty($bloque->content)? '' : $bloque->content}}">                                                                    
                                                                    </div>
                                                            </div>

                                                        <div class="row form-group html" style="display:<?= ((!empty($see) && $see == 'article') || (!empty($bloque->type) && ($bloque->type == 'H' || $bloque->type == 'A')))? 'block' : 'none'; ?>">
                                                            <div class="col-md-12 ml-15"><p>{{ trans('admin-app.placeholder.html') }}</p></div>
                                                                <div class="col-md-12"><textarea id="content-summernote" class="summernote" data-plugin-summernote data-plugin-options='{ "height": 300, "codemirror": { "theme": "ambiance" } }' placeholder="{{ trans('admin-app.placeholder.consulta') }}" class="form-control" rows="5"><?= empty($bloque)? ' ' : $bloque->content; ?></textarea></div>
                                                        </div>
                                                            <input id="html" type="hidden" name="html" value="">
                                                </div>
                                                
                                </section>
                             </div>
                            <div class="col-md-2">
                                <section class="panel">

                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                       <div class="checkbox-custom checkbox-default">
                                                                                <input name="enabled" type="checkbox" <?= (!empty($bloque->enabled) && $bloque->enabled == 1)? 'checked' : '' ; ?>  id="checkboxExample3">
                                                                                <label for="checkboxExample3">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                                        </div>
                                                                </div>
                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_resources">{{ trans('admin-app.title.save') }}</button>
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                    
                                                            <input type="hidden" class="id_input" name="id" value="{{!empty($bloque->id_web_resource)? $bloque->id_web_resource : '0' }}" >
                                                            <input type="hidden" name="file_url" value="{{!empty($bloque->url_resource)? $bloque->url_resource : ' ' }}"/>
                                                            <input class="cod_sec" type="hidden" name="cod_sec" value="<?= isset($_GET['crs'])?$_GET['crs'] : '' ?>" >
                                                </div>
                                               
                                </section>
                            </div>
                             </form>
		</div>

			
		</div>

		<div id="thumbnails" class="hidden"></div>
                <div id="sliders" class="imagen" style="display:<?= ((empty($see) && (empty($bloque->type) || $bloque->type == 'I')) || ((!empty($bloque->type) && $bloque->type == 'C') || (!empty($see) && $see == 'calendar')))? 'block' : 'none'; ?>">
			<div class="row">
				<div class="col-sm-10">
						<div class="tab-content no-paddings">
							<div id="new" class="tab-pane <?=(empty($tab) || $tab == 'main') ? 'active' : '' ?>">
								<section class="panel no-margins">
									<div class="panel-body" id="data-loader" data-loading-overlay data-loading-overlay-options='{ "startShowing": false }'>
										<div class="mb-md col-sm-12 no-paddings">
											<div class="col-sm-6">
												<form action="/admin/sliders/upload" class="dropzone dz-square" id="sliderupload">
													<div class="dz-message">
														{{ trans('admin-app.placeholder.imagen') }}
														<i class="fa fa-hand-o-up"></i>
													</div>
												</form>
											</div>
											<div class="col-sm-6 img_place">
                                                                                            @if(empty($bloque->url_resource))
												<i class="fa fa-file-image-o"></i>
                                                                                            @else
                                                                                                <img src="{{$bloque->url_resource}}" class="img-responsive">
                                                                                            @endif
											</div>
										</div>
										
									</div>
								</section>
							</div>
                                                   
							
						</div>

				</div>
			</div>

			
		</div>
	</section>
		
@stop