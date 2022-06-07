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

<form id="auc_index">
	<div id="cms">
            <div class="row">
                    <div class="col-md-10">
                        <section class="panel">                                      
                        <div class="panel-body">
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
                                        <?php $i = true ?>
                                        @foreach($data['idiomes'] as $idiomes => $keylang)
                                           <div id="{{$idiomes}}" class="tab-pane <?= ($i == true)? 'active' : ''; ?>">
                                               <div class='row'>
                                               @if(!empty($data['lang']))
                                                    @foreach($data['lang'] as $lang)
                                                        @if(strtoupper($idiomes) == $lang->id_lang)
                                                            <div class="col-md-6">
                                                                 <p>{{ trans('admin-app.placeholder.titulo') }}</p> 
                                                                 <input maxlength="255" type="text" name="title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.titulo') }}" class="form-control" value="{{empty($lang->title)? '' : $lang->title}}">
                                                            </div>
                                                           
                                                            <div class="col-lg-6">
                                                                <p>{{ trans('admin-app.placeholder.subtitle') }}</p>  
                                                                <input maxlength="255" type="text" name="subtitle_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.subtitle') }}" class="form-control" value="{{empty($lang->subtitle)? '' : $lang->subtitle}}">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <br>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p>{{ trans('admin-app.placeholder.key_name') }}</p>  
                                                                <input maxlength="100" type="text" name="key_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.key_name') }}" class="form-control" value="{{empty($lang->key_name)? '' : $lang->key_name}}">
                                                            </div>
                                                        @endif
                                                    @endforeach
                                               @else
                                               <div class="col-md-6">
                                                    <p>{{ trans('admin-app.placeholder.titulo') }}</p> 
                                                    <input maxlength="255" type="text" name="title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.titulo') }}" class="form-control" value="">
                                               </div>
                                               <div class="col-lg-6">
                                                   <p>{{ trans('admin-app.placeholder.subtitle') }}</p>  
                                                   <input maxlength="255" type="text" name="subtitle_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.subtitle') }}" class="form-control" value="">
                                                </div>
                                               <div class="col-lg-12">
                                                   <br>
                                               </div>
                                               <div class="col-lg-6">
                                                   <p>{{ trans('admin-app.placeholder.key_name') }}</p>  
                                                   <input maxlength="100" type="text" name="key_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.key_name') }}" class="form-control" value="">
                                               </div>
                                               
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
                                               <div class="checkbox-custom checkbox-default">
                                                        <input name="enabled" type="checkbox"  <?= (!empty($data['inf']->enabled) && $data['inf']->enabled == 1)? 'checked' : '' ; ?> id="checkboxActiavted">
                                                        <label for="checkboxActiavted">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                </div>
                                        </div>
                                        <div class="mb-md hidden-lg hidden-xl"></div>

                                </div>
                                <div class="row form-group">
                                        <div class="col-lg-12">
                                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_auc_index">{{ trans('admin-app.title.save') }}</button>
                                        </div>

                                        <div class="mb-md hidden-lg hidden-xl"></div>

                                </div>

                        </div>
                    </section>
                </div>
            </div>
            <div class="row">
                        
                             <div class="col-md-10">
                                <section class="panel">
                                        
                                        <div class="panel-body">
                                                <input class="id_input" type="hidden" name="id" value="{{empty($data['inf']->id_web_auc_index)? '0' : $data['inf']->id_web_auc_index}}" >
                                                <div class="row form-group">
                                                        <div class="col-lg-6">
                                                            <p>{{ trans('admin-app.placeholder.nombre') }}</p>  
                                                          <input maxlength="50" type="text" name="title" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="{{empty($data['inf']->title)? '' : $data['inf']->title}}">
                                                            <input type="hidden" name="type" value="S">       
                                                        </div>
                                                   
                                                       
                                                                                                       

                                                   
                                                    <div class="col-lg-6">
                                                    <div class="familia_sessions" style="display:hide">
            
                                                        
                                                             <p>{{ trans('admin-app.placeholder.padre') }}</p>  
                                                             <select class="form-control mb-md" name="parent">
                                                                
                                                                 @foreach($data['padre'] as $padre)
                                                                 
                                                                     <option <?= (empty($data['inf']->parent) || $data['inf']->parent == $padre->id_web_auc_index)? 'selected' : ''; ?> value="{{ $padre->id_web_auc_index }}">{{ $padre->title }}</option>
                                                                 @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <?php $i = 0 ?>
                                                <div class="sessions form-group" style="display:hide">
                                                    <div class="mb-10">
                                                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary new_session_auc_index"><i class="fa fa-plus-square-o"></i></button>
                                                            {{ trans('admin-app.placeholder.add_sessions') }}
                                                    </div>    
                                                    @foreach($data['Lots'] as $lots)
                                                    <div class="row session_{{$i}}">
                   
                                                        <div class="col-lg-5"> 
                                                            <p>{{ trans('admin-app.placeholder.sessions') }}</p> 
                                                                <select class="form-control mb-md" name="id_auc_session_{{$i}}">
                                                                        @foreach($data['LotsSession'] as $session)
                                                                          <option value="{{$session->id_auc_sessions}}"  <?php  if($session->id_auc_sessions == $lots->id_auc_session){ echo('selected=""'); }else{echo(' ');} ?> >{{$session->name}}</option>
                                                                        @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-lg-5">

                                                              <p>{{ trans('admin-app.placeholder.lotes') }}</p>  
                                                              <input type="text" name="lots_{{$i}}" placeholder="{{ trans('admin-app.placeholder.lotes') }}" class="form-control" value="{{empty($lots->lots)? ' ' : $lots->lots}}">

                                                        </div>
                                                        <div class="col-lg-2">
                                                            <p style="margin-bottom:25px;"></p>
                                                            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger delete_session_auc_index" data_delete=".session_{{$i}}"><i class="fa fa-remove"></i></button>
                                                        </div> 
                                                         
                                                        </div>
                                                        <?php $i++; ?>
                                                        @endforeach
                                                        <input type="hidden" class="num_session" name="num_sessions" value="{{$i}}">
                                                       
                                                        <div id="container-section"></div>
                                                </div>
                                                <div class="familia form-group"  style="display:none" >
                                                    <div class="scrollable" data-plugin-scrollable style="height: 350px;">   
                                                    <div class=" scrollable-content">

                                                                <div class="checkbox-custom checkbox-default">
                                                                        <input name="sections[]" <?= (!empty($data['inf']->sections) && $data['inf']->sections=='ALL')?'checked':''; ?> type="checkbox" id="all" value="ALL">
                                                                        <label for="all">TODAS CATEGORIAS</label>
                                                                </div>
                                                                <?php 
                                                                    if(!empty($data['inf']->sections) ){
                                                                        $sect_fxsec = explode(",",$data['inf']->sections);
                                                                    }else{
                                                                        $sect_fxsec = array();
                                                                    }
                                                                ?>
                                                                
                                                                @foreach ($data['fxsec'] as $sections)
                                                                    @if(in_array(trim($sections->cod_sec),$sect_fxsec))
                                                                        <div class="checkbox-custom checkbox-default not-all">
                                                                                <input name="sections[]" checked type="checkbox" id="checkboxCod_sec_{{$sections->cod_sec}}" value="{{$sections->cod_sec}}">
                                                                                <label for="checkboxCod_sec_{{$sections->cod_sec}}">{{ $sections->des_sec }} ({{ $sections->cod_sec}})</label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                @foreach ($data['fxsec'] as $sections)
                                                                    @if(!in_array(trim($sections->cod_sec),$sect_fxsec))
                                                                        <div class="checkbox-custom checkbox-default not-all">
                                                                                <input name="sections[]"  type="checkbox" id="checkboxCod_sec_{{$sections->cod_sec}}" value="{{$sections->cod_sec}}">
                                                                                <label for="checkboxCod_sec_{{$sections->cod_sec}}">{{ $sections->des_sec }} ({{ $sections->cod_sec}})</label>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                        </div>
                                                
                                </section>
                             </div>
                            
                 
                </div>
            

            </div>
            <input type="hidden" name="file_url" value="<?= !empty($data['inf']->url_resource)?$data['inf']->url_resource:''; ?>"/>
        </form>
    
    <div id="thumbnails" class="hidden"></div>
    <div id="sliders" class="sessions" style="display:block">
            <div class="row">
                    <div class="col-sm-10">
                                    <div class="tab-content no-paddings">
                                            <div id="new" class="tab-pane @if (empty($tab) || $tab == 'main')active @endif">
                                                    <section class="panel no-margins">
                                                            <div class="panel-body" id="data-loader" data-loading-overlay data-loading-overlay-options='{ "startShowing": false }'>
                                                                    <div class="mb-md col-sm-12 no-paddings">
                                                                            <div class="col-sm-6">
                                                                                    <form action="/admin/sliders/upload" class="dropzone dz-square" id="sliderupload">
                                                                                            <div class="dz-message">
                                                                                                    {{ trans('admin-app.placeholder.imagen') }}
                                                                                                    <i class="fa fa-hand-o-up"></i>
                                                                                            </div>
                                                                                        <input type="hidden" value="seo" name="url_img">
                                                                                    </form>
                                                                            </div>
                                                                            <div class="col-sm-6 img_place">
                                                                                
                                                                                    <i class="fa fa-file-image-o"></i>
                                                                                    @if(!empty($data['inf']->url_resource))
                                                                                    <img data-dz-thumbnail="" src="{{$data['inf']->url_resource}}" class="img-responsive">
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
    <div id="block-sesion" style="display:none">
        <div class="row">
            <div class="col-lg-5"> 
                <p>{{ trans('admin-app.placeholder.sessions') }}</p> 
                    <select id="id_auc_session" class="form-control mb-md">
                            @foreach($data['LotsSession'] as $session)
                              <option value="{{$session->id_auc_sessions}}" >{{$session->name}}</option>
                            @endforeach
                    </select>
            </div>
            <div class="col-lg-5">

                  <p>{{ trans('admin-app.placeholder.lotes') }}</p>  
                  <input type="text" id="lots" placeholder="{{ trans('admin-app.placeholder.lotes') }}" class="form-control" value=" ">

            </div>
            <div class="col-lg-2">
               <p style="margin-bottom:25px;"></p>
               <button id="delete" type="button" class="mb-xs mt-xs mr-xs btn btn-danger delete_session_auc_index "><i class="fa fa-remove"></i></button>

            </div>
            </div>
    </div>

</section>

@stop
