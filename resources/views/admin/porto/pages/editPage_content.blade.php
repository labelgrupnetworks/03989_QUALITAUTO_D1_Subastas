@extends('admin::layouts.logged')
@section('content')

@php
	$urlStaticPage = \Config::get('app.url').'/es/pagina/'.$content->key_web_page;
@endphp

	<section role="main" class="content-body">

		<div id="resources">
			<div class="row">
                            <form id="edit_page_content">
                                 <input type="hidden" class="id_input" name="id" value="{{!empty($content->id_web_page)? $content->id_web_page : '0' }}" >
                                 <div class="col-md-2">
                                <section class="panel">

                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_page">{{ trans('admin-app.title.save') }}</button>
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>


                                                </div>

                                </section>
                            </div>
                             <div class="col-md-10">
                                <section class="panel">

                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-5">
                                                                    <p>{{ trans('admin-app.placeholder.nombre') }}</p>
                                                                         <input type="text" name="name_web_page" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="{{ empty($content->name_web_page)? '' : $content->name_web_page}}">
                                                                </div>
																<div class="col-lg-3"></div>
																<div class="col-lg-4">
																	<p>{{ trans('admin-app.placeholder.url') }}</p>
                                                                         <input type="text" id="url_static_page" name="" placeholder="{{ trans('admin-app.placeholder.url') }}" class="form-control" value="{{ $urlStaticPage }}" readonly>
																</div>

                                                        </div>
                                                    <div class="row form-group">
                                                        <div class="col-lg-6">
                                                                    <p>{{ trans('admin-app.placeholder.meta_title') }}</p>
                                                                         <input type="text" name="webmetat_web_page" placeholder="{{ trans('admin-app.placeholder.meta_title') }}" class="form-control" value="{{ empty($content->webmetat_web_page)? '' : $content->webmetat_web_page}}">
                                                                </div>

                                                          <div class="col-lg-6">
                                                               <p>{{ trans('admin-app.placeholder.no_index_follow') }}</p>
                                                                <div class="checkbox-custom checkbox-default">

                                                                                <input name="webnoindex_web_page" type="checkbox" <?= (empty($content->webnoindex_web_page) || $content->webnoindex_web_page == 0)? '' : 'checked'; ?>  id="checkboxExample3">
                                                                                <label for="checkboxExample3">{{ trans('admin-app.placeholder.no_index_follow') }}</label>
                                                                        </div>
                                                          </div>
                                                    </div>
                                                    <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <p>{{ trans('admin-app.placeholder.meta_description') }}</p>
                                                                         <input type="text" name="webmetad_web_page" placeholder="{{ trans('admin-app.placeholder.meta_description') }}" class="form-control" value="{{ empty($content->webmetad_web_page)? '' : $content->webmetad_web_page}}">
                                                                </div>
                                                        </div>

                                                        <div class="row form-group ">

                                                            <div class="col-md-3">

                                                            </div>

                                                        <div class="row form-group">
                                                            <div class="col-md-12 ml-15"><p>{{ trans('admin-app.placeholder.html') }}</p></div>
                                                                <div class="col-md-12">
                                                                    <div id="content-summernote" class="summernote" data-plugin-summernote data-plugin-options="{ 'height': 900, 'codemirror': { 'theme': 'ambiance' } }" placeholder="{{ trans('admin-app.placeholder.consulta') }}" class="form-control" rows="5">
                                                                        <?= empty($content->content_web_page)? ' ' : $content->content_web_page; ?>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                            <input id="html" type="hidden" name="html" value="">
                                                </div>

                                </section>
                             </div>

                             </form>
		</div>


		</div>
	</section>

<script>
	//Función para que redirija a la URL al darle al texto
	$(document).ready(function(){
		$("#url_static_page").click(function() {
			var url = '<?php echo $urlStaticPage ?>';
			window.open(url, '_blank');
		});
	});
</script>

@stop
