@extends('admin::layouts.logged')
@section('content')
<?php
if(!empty($data['noticia'])){
    $noticia = $data['noticia'];
}else{
    $noticia = array();
}

# Guardamos la lista de extensiones para comprobar si es una imagen o un video
$extensionesImagen = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'tif', 'svg', 'svgz', 'ico', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'TIFF', 'TIF', 'SVG', 'SVGZ', 'ICO');
$extensionesVideo = array('mp4', 'webm', 'ogg', 'avi', 'mkv', 'MP4', 'WEBM', 'OGG', 'AVI', 'MKV');
?>
<form id="save_blog">
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
                <div id="cms">
                    <div class="row">
						<input type="hidden" class="id_input" name="id"
							value="{{!empty($noticia) && !empty(head($noticia['lang'])->title_web_blog)? head($noticia['lang'])->id_web_blog : '0'}}">
						<input type="hidden" name="file_url"
							value="{{!empty($noticia) && !empty(head($noticia['lang'])->img_web_blog)?head($noticia['lang'])->img_web_blog:''}}" />
						<div class="col-md-10">
							<section class="panel">
								<div class="panel-body">
									<div class="row form-group">
										<div class="col-xs-12 col-lg-4">
											<p>{{ trans('admin-app.placeholder.nombre') }}</p>
											<input type="text" required name="title"
												placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control"
												value="{{!empty($noticia) && !empty($noticia['lang'] && !empty($noticia['lang']['ES']) && !empty(head($noticia['lang'])->title_web_blog) )? head($noticia['lang'])->title_web_blog : '' }}">
										</div>
										<div class="col-xs-12 col-lg-4">
											<p>{{ trans('admin-app.placeholder.fecha_publicacion') }}</p>
											<div class="input-group">
												<span class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</span>
												<input type="datetime" data-plugin-datepicker data-date-format="yyyy-mm-dd"
													class="form-control" name="date"
													value='{{!empty($noticia) &&  !empty(head($noticia['lang'])->publication_date_web_blog)?  date( "Y-m-d",strtotime(head($noticia['lang'])->publication_date_web_blog)) : ''}}'>
											</div>
										</div>
										<div class="col-xs-12 col-lg-4">
											<p>{{ trans('admin-app.placeholder.author') }}</p>
											<input  type="text" maxlength="100" name="author" placeholder="{{ trans('admin-app.placeholder.author') }}" class="form-control"
												value="{{!empty($noticia) && !empty($noticia['lang'] && !empty($noticia['lang']['ES']) && !empty(head($noticia['lang'])->author_web_blog) )? head($noticia['lang'])->author_web_blog : '' }}">
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-2">
							<section class="panel">

								<div class="panel-body">
									<div class="row form-group">

										<div class="mb-md hidden-lg hidden-xl"></div>

									</div>
									<div class="row form-group">
										<div class="col-lg-12">
											<button type="submit"
												class="mb-xs mt-xs mr-xs btn btn-lg btn-primary">{{ trans('admin-app.title.save') }}</button>
										</div>

										<div class="mb-md hidden-lg hidden-xl"></div>

									</div>
								</div>

							</section>
						</div>
					</div>
                    <div class="row">
                         <div class="panel col-sm-10">
                        <div class="tabs tabs-bottom tabs-primary">
                                            <ul class="nav nav-tabs nav-justified">
                                                 {{$i = true}}
                                                 @foreach($data['idiomes'] as $idiomes => $keylang)
                                                    <li class="<?= ($i == true)? 'active' : ''; ?>" >
                                                            <a href="#{{$idiomes}}" data-toggle="tab" class="text-center"> {{$keylang}}</a>
                                                    </li>
                                                 {{$i = false}}
                                                 @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                    <?php $i = true ; ?>
                                                    @foreach($data['idiomes'] as $idiomes => $keylang)
                                                       <div id="{{$idiomes}}" class="tab-pane <?= ($i == true)? 'active' : ''; ?>">
                                                           <div class='row'>
                                                               <?php
                                                                    $blog_lang = NULL;
                                                                     if(!empty($noticia['lang']) && !empty($noticia['lang'][strtoupper($idiomes)])) {
                                                                         $blog_lang = $noticia['lang'][strtoupper($idiomes)];
                                                                     }
                                                               ?>

                                                                    <div class="col-lg-12">
                                                                       <div class="checkbox-custom checkbox-default">
                                                                                <input name="enabled_{{strtoupper($idiomes)}}" type="checkbox" <?= (!empty($blog_lang) && $blog_lang->enabled_web_blog_lang == 1)? 'checked' : '' ; ?>  id="checkboxExample3">
                                                                                <label for="checkboxExample3">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                                        </div>
                                                                    </div>
                                                                        <div class="col-md-6">
                                                                            <p>{{ trans('admin-app.placeholder.titulo') }}</p>

                                                                            <input  type="text" maxlength="255" name="title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.titulo') }}" class="form-control" value="<?=!empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->titulo_web_blog_lang):""?>">
                                                                       </div>
                                                                        <div class="col-md-6">
                                                                            <p>{{ trans('admin-app.placeholder.url') }}</p>
                                                                            <input  type="text" maxlength="255" name="url_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.url') }}" class="form-control" value="<?= !empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->url_web_blog_lang):"" ?>" >
																	   </div>
                                                                       <div class="col-lg-6">
                                                                           <p>{{ trans('admin-app.placeholder.cita') }} {{ trans('admin-app.title.insertar_cita') }} </p>
                                                                           <input  type="text" maxlength="255" name="cita_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.cita') }}" class="form-control" value="<?= !empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->cita_web_blog_lang):"" ?>">
                                                                        </div>
                                                                       <div class="col-md-6">
                                                                            <p>{{ trans('admin-app.placeholder.meta_title') }}</p>
                                                                            <input  type="text"  name="meta_title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.meta_title') }}" class="form-control" value="<?= !empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->metatitle_web_blog_lang):"" ?>" >
                                                                       </div>
                                                                       <div class="col-lg-6">
                                                                           <p>{{ trans('admin-app.placeholder.meta_description') }}</p>
                                                                           <input  type="text"  name="meta_desc_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.meta_description') }}" class="form-control" value="<?= !empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->metadescription_web_blog_lang):"" ?>" >
                                                                        </div>

                                                                       <div class="col-md-6">
                                                                            <p>{{ trans('admin-app.placeholder.video') }}</p>
                                                                            <input  type="text" maxlength="255" name="video_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.video') }}" class="form-control" value="<?= !empty($blog_lang)?str_replace("\"", "&quot;", $blog_lang->video_web_blog_lang):"" ?>" >

                                                                       </div>

                                                                        <div class="col-md-12 ml-15">
																			<p>{{ trans('admin-app.title.contenido') }}</p>
																		</div>
                                                                        <div class="col-md-12" >

                                                                               <textarea lang="{{strtoupper($idiomes)}}" id="summernote_{{strtoupper($idiomes)}}"  class="summernote summernote_descrip" class="form-control" rows="5">
                                                                                    <?= !empty($blog_lang)?$blog_lang->texto_web_blog_lang:"" ?>
                                                                                </textarea>

                                                                                <input id="cont_{{strtoupper($idiomes)}}" name="cont_{{strtoupper($idiomes)}}" type="hidden"  value="">
                                                                        </div>

                                                                </div>
                                                             </div>
                                                       {{$i = false}}
                                                    @endforeach

                                            </div>
                                       </div>
                         </div>
                    </div>
                    <div class="row ">
                        <section class="panel col-md-10">
                            <div class="panel-body">
                                <div class="col-sm-12" style="padding-left:0px;">
                                    <p>{{ trans('admin-app.title.categories_blog_principal') }}</p>
                                    <select class="form-control mb-3" name="categ_blog_principal">
                                         <?php $checked = false;?>
                                        @foreach($data['categories'] as $categ)
                                            <option value="{{$categ->id_category_blog}}"  <?= !empty($noticia['lang']) && !empty(head($noticia['lang'])->primary_category_web_blog) && head($noticia['lang'])->primary_category_web_blog == $categ->id_category_blog? 'selected' : ''?>>{{$categ->title_category_blog}}</option>
                                        @endforeach
                                    </select> </br></br>
                                </div>


                                <p>{{ trans('admin-app.title.categories_blog') }}</p>
                                @foreach($data['categories'] as $categ)
                                <div class="checkbox-custom checkbox-default col-sm-2">
                                    <input <?= !empty($noticia['categories']) && in_array($categ->id_category_blog,$noticia['categories'])?'checked':'';?> name="cate_blog[]" value="{{$categ->id_category_blog}}" type="checkbox"  id="checkbox{{$categ->id_category_blog}}">
                                    <label for="checkbox_cate_{{$categ->id_category_blog}}">{{$categ->title_category_blog}}</label>
                                 </div>
                                @endforeach

                            </div>
                        </section>
                    </div>

                    <div class="row ">
                        <section class="panel col-md-10">
                                <div class="panel-body">
                                <p>{{ trans('admin-app.title.secciones') }}</p>
                                @foreach($data['sec'] as $key_sec => $value_sec)
                                <div class="checkbox-custom checkbox-default col-sm-2">
                                    <input  <?= !empty($noticia['lot_categories_web_blog']) && in_array($key_sec,$noticia['lot_categories_web_blog'])?'checked':'';?> name="sec[]" value="{{$key_sec}}" type="checkbox"  id="checkbox{{$key_sec}}">
                                    <label for="checkbox_sec_{{$key_sec}}">{{$value_sec}}</label>
                                 </div>
                                @endforeach
                            </div>
                        </section>
                    </div>
                    <div class="row ">
                        <section class="panel col-md-10">
                                <div class="panel-body">
                                <p>{{ trans('admin-app.title.subcategories') }}</p>
                                 <div class=" col-xs-3">
                                     <?php
                                     $max= count($data['sub_categ'])/4;

                                     $i=0;
                                     ?>
                                @foreach($data['sub_categ'] as $key_categ => $value_categ)
                                 <?php

                                     $i++;
                                 ?>
                                <div class="checkbox-custom checkbox-default">
                                    <input <?= !empty($noticia['lot_sub_categories_web_blog']) && in_array($key_categ,$noticia['lot_sub_categories_web_blog'])?'checked':'';?>  name="sub_categ[]" value="{{$key_categ}}" type="checkbox"  id="checkbox{{$key_categ}}">
                                    <label for="checkbox_sub_categ{{$key_categ}}">{{$value_categ}}</label>
                                    </div>
                                 <?php

                                    if ($max<=$i) {
                                     $i=0;
                                      echo '  </div><div class="col-xs-3">';
                                    }

                                 ?>
                                @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
</section>
</form>
<section role="main" class="content-body">
                    <div class="row">
                     <div class="panel col-sm-10">
                         <p> {{ trans('admin-app.title.size_img') }}</p>
		<div id="thumbnails" class="hidden"></div>
                <div id="sliders" class="imagen" style="display:block">

                    <div class="tab-content no-paddings">
                            <div id="new" class="tab-pane <?= (empty($tab) || $tab == 'main')? 'active':'' ?>">
                                    <section class="panel no-margins">
                                            <div class="panel-body" id="data-loader" data-loading-overlay data-loading-overlay-options='{ "startShowing": false }'>
                                                    <div class="mb-md col-sm-12 no-paddings">
                                                            <div class="col-sm-6">
                                                                    <form action="/admin/sliders/upload" class="dropzone dz-square" id="sliderupload">
                                                                        <input type="hidden" name="url_img" value="blog">
                                                                            <div class="dz-message">
                                                                                    {{ trans('admin-app.placeholder.imagen') }}
                                                                                    <i class="fa fa-hand-o-up"></i>
                                                                            </div>
                                                                    </form>
                                                            </div>
                                                            <div class="col-sm-6 img_place">
                                                                @if(!empty($noticia) && !empty( head($noticia['lang'])->img_web_blog))

																	@php
																		$img_web_separado = explode("/", head($noticia['lang'])->img_web_blog);
																		$nombreArchivo = explode(".", end($img_web_separado));
																		$extension = end($nombreArchivo);
																	@endphp

																	@if (in_array($extension, $extensionesImagen))
                                                                    	<img src="{{head($noticia['lang'])->img_web_blog}}" class="img-responsive">
																	@elseif (in_array($extension, $extensionesVideo))
																		<video class="img-responsive" src="{{ head($noticia['lang'])->img_web_blog }}" controls autoplay></video>
																	@endif
                                                                @else
                                                                     <i class="fa fa-file-image-o"></i>
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




<br>
@if(!empty($noticia) && !empty($noticia['lang']) && !empty(head($noticia['lang']) && !empty(head($noticia['lang'])->id_web_blog) ))
<section role="main" class="content-body">
	<div class="row">
		<div class=" col-sm-10">
			<div class="well">
				<h3> Subir Imágenes Slider</h3> Las imágenes se ordenarán por nombre
				<form action="/admin/sliders/upload" class="dropzone" id="my-awesome-dropzone" method="POST">
					<input type="hidden" name="url_img" value="blog/{{head($noticia['lang'])->id_web_blog}}">
				</form>
			</div>

			<div class="well">
				<h3>Imágenes Slider</h3>
					<div class="row">
						@php
						$path  = 'img/blog/'.head($noticia['lang'])->id_web_blog;

							$imagenes = array();
							// Arreglo con todos los nombres de los archivos
							if(is_dir($path)){
								$imagenes = array_diff(scandir($path), array('.', '..'));
							}
						@endphp
						@foreach ($imagenes as $k => $imagen)
						<div class="col-xs-6 col-md-3 image-wrapper text-center mt-1" id="imagen{{$k}}">
							<img height="200px"  src="/{{ $path."/".$imagen }}?a={{rand()}}">
							<br>
							<center>
								<a onclick="javascript:borrarImagenSlider({{ $k }},'{{ $path."/".$imagen }}')">
									<i class="fa fa-2x fa-times red"></i>
								</a>
							</center>
						</div>

						@endforeach

					</div>

				</div>

		</div>
	</div>
<section role="main" class="content-body">
@endif


<script>
	function borrarImagenSlider(item, url) {


		$.post("/admin/sliders/delete", { pathImg: url }, function (data) {

			$("#imagen" + item).remove();

		});

	}
</script>
@stop
