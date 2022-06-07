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
<form id="seo-categ">
    {{ csrf_field() }}
	<div id="cms">
            <div class="row">
                    <div class="col-md-10">
                        <section class="panel">                                      
                        <div class="panel-body">
                            <h3><?=  $data['cod_sec']->des_sec ?></h3>
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
                                     <input type="hidden" name="codsec" value="<?=  $data['cod_sec']->cod_sec ?>">
                                        <?php $i = true;?>
                                        @foreach($data['idiomes'] as $idiomes => $keylang)
                                           <div id="{{$idiomes}}" class="tab-pane <?= ($i == true)? 'active' : ''; ?>">
                                               <div class='row'>
                                                @if(!empty($data['content']))
                                                    @foreach($data['content'] as $content)
                                                         @if(strtoupper($idiomes) == $content->codlang_seo_sec)
                                                         <input id="id_{{strtoupper($idiomes)}}" type="hidden" name="id_{{strtoupper($idiomes)}}" value="<?= !empty($content->id_seo_sec)? $content->id_seo_sec :' ';?>">
                                                         <input type="hidden" id="webcont_{{strtoupper($idiomes)}}" name="webcont_{{strtoupper($idiomes)}}" value=" " >
                                                         <div class="col-md-6">
                                                              <p>{{ trans('admin-app.title.nombre') }}</p> 
                                                              <input type="text" name="webname_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.nombre') }}" class="form-control" value="<?= !empty($content->webname_seo_sec)? $content->webname_seo_sec :' ';?>">
                                                         </div>

                                                         <div class="col-lg-6">
                                                             <p>{{ trans('admin-app.title.url_amigable') }}</p>  
                                                             <input type="text" name="webfriend_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.url_amigable') }}" class="form-control" value="<?= !empty($content->webfriend_seo_sec)? $content->webfriend_seo_sec :'';?>">
                                                         </div>
                                                         <div class="col-lg-12">
                                                             <br>
                                                         </div>
                                                         <div class="col-lg-6">
                                                             <p>{{ trans('admin-app.title.meta_description') }}</p>  
                                                             <input type="text" name="webmetad_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.meta_description') }}" class="form-control" value="<?= !empty($content->webmetad_seo_sec)? $content->webmetad_seo_sec:' ';?>">
                                                         </div>
                                                         <div class="col-lg-6">
                                                             <p>{{ trans('admin-app.title.meta_titulo') }}</p>  
                                                             <input type="text" name="webmetat_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.meta_titulo') }}" class="form-control" value="<?= !empty($content->webmetat_seo_sec)? $content->webmetat_seo_sec :' ';?>">
                                                         </div>
                                                             <div class="col-lg-12">
                                                             <br>
                                                         </div>
                                                             <div class="col-md-12" id="content-summernote_{{strtoupper($idiomes)}}" >
                                                                 <p>{{ trans('admin-app.title.contenido') }}</p>
                                                               <textarea class="summernote content-summernote" data-plugin-summernote data-plugin-options='{ "height": 300, "codemirror": { "theme": "ambiance" } }' placeholder="{{ trans('admin-app.placeholder.consulta') }}" class="form-control" rows="5"><?= empty($content->webcont_seo_sec)? ' ' : $content->webcont_seo_sec; ?></textarea></div>

                                                         @endif

                                                      @endforeach
                                                     @else
                                                        <input id="id_{{strtoupper($idiomes)}}" type="hidden" name="id_{{strtoupper($idiomes)}}" value="">
                                                        <input type="hidden" id="webcont_{{strtoupper($idiomes)}}" name="webcont_{{strtoupper($idiomes)}}" value="" >
                                                        <div class="col-md-6">
                                                             <p>{{ trans('admin-app.title.nombre') }}</p> 
                                                             <input type="text" name="webname_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.nombre') }}" class="form-control" value="">
                                                        </div>

                                                        <div class="col-lg-6 hidden">
                                                            <p>{{ trans('admin-app.title.url_amigable') }}</p>  
                                                            <input type="text" name="webfriend_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.url_amigable') }}" class="form-control" value="">
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <br>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p>{{ trans('admin-app.title.meta_description') }}</p>  
                                                            <input type="text" name="webmetad_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.meta_description') }}" class="form-control" value="">
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <p>{{ trans('admin-app.title.meta_titulo') }}</p>  
                                                            <input type="text" name="webmetat_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.title.meta_titulo') }}" class="form-control" value="">
                                                        </div>
                                                            <div class="col-lg-12">
                                                            <br>
                                                        </div>
                                                        <div class="col-md-12" id="content-summernote_{{strtoupper($idiomes)}}">
                                                            <p>{{ trans('admin-app.title.contenido') }}</p>
                                                        <textarea  class="summernote content-summernote" data-plugin-summernote data-plugin-options='{ "height": 300, "codemirror": { "theme": "ambiance" } }' placeholder="{{ trans('admin-app.placeholder.consulta') }}" class="form-control" rows="5"></textarea></div>      
                                                       @endif
                                               </div>
                                           </div>
                                           {{$i = false}}
                                        @endforeach

                                </div>
                           </div>
                        </div></section>
                    </div>
                <div class="col-md-2">
                    <section class="panel">                                      
                        <div class="panel-body">
                                <div class="row form-group">
                                        <div class="col-lg-12">
                                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_seo">{{ trans('admin-app.title.save') }}</button>
                                        </div>

                                        <div class="mb-md hidden-lg hidden-xl"></div>

                                </div>

                        </div>
                    </section>
                </div>
                <script>
                  
                    var idiomes = (<?= json_encode($data['idiomes'],JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS) ?>);

                </script>
            </div>
           

</section>

@stop
