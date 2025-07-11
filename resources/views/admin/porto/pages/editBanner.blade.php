@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
<form id="new_banner">
	<div id="cms">
            <div class="row">

                             <div class="col-md-10">
                                <section class="panel">
                                                @if(!empty($data['infBanner']) && $data['infBanner']->type_web_banner == 'N')
                                                     <?php
                                                        $type_web_banner = 'N';
                                                        $var_type = array('A');
                                                     ?>
                                                @elseif(!empty($data['infBanner']) && $data['infBanner']->type_web_banner == 'C')
                                                     <?php
                                                        $type_web_banner = 'C';
                                                        $var_type = array('C');
                                                     ?>
                                                @else
                                                    <?php
                                                        //
                                                        $type_web_banner = 'B';
                                                        $var_type = array('H','I');
                                                    ?>
                                                @endif
                                                <div class="panel-body">
                                                        <input class="cod_sec" type="hidden" name="cod_sec" value="<?= isset($_GET['cbs'])?$_GET['cbs'] : '' ?>" >
                                                        <input class="id_input" type="hidden" name="id" value="{{empty($data['infBanner']->id_web_banner)? '0' : $data['infBanner']->id_web_banner}}" >
                                                        <div class="row form-group">
                                                                <div class="col-lg-4">
                                                                    <p>{{ trans('admin-app.placeholder.nombre') }}</p>
                                                                  <input type="text" name="name" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="{{ empty($data['infBanner']->title)? '' : $data['infBanner']->title}}">

                                                                </div>
                                                            <div class="col-lg-4">
                                                                <p>{{ trans('admin-app.placeholder.key_name') }}</p>
                                                                <input type="text" name="key_name" placeholder="{{ trans('admin-app.placeholder.key_name') }}" class="form-control" value="{{ empty($data['infBanner']->key_name)? '' : $data['infBanner']->key_name}}">
                                                             </div>
                                                            <div class="col-lg-4">
                                                                <?php /*
                                                                <p>{{ trans('admin-app.placeholder.type') }}</p>
                                                                <select class="form-control input-sm mb-md target" name="type">
                                                                    <option <?= (empty($data['infBanner']->type_web_banner) || $data['infBanner']->type_web_banner == 'N')? 'selected' : ''; ?> value="N">{{ trans('admin-app.selected.noticia') }}</option>
                                                                    <option <?= (empty($data['infBanner']->type_web_banner) || $data['infBanner']->type_web_banner == 'B')? 'selected' : ''; ?> value="B" >{{ trans('admin-app.selected.banner') }}</option>
                                                                </select>

                                                                 */
                                                                ?>
                                                                 <input class="type" type="hidden" name="type" value="{{$type_web_banner}}" >
                                                            </div>
                                                        </div>
                                                            <div class="panel-body" >
                                                                <div class="scrollable" data-plugin-scrollable style="height: 350px;">
                                                                    <div class=" scrollable-content">
                                                                        <div id="portlet-1" data-plugin-portlet>
                                                                        @foreach($data['BannerResources'] as $banner_resource)
                                                                            @if(array_key_exists($banner_resource->id_web_resource,$data['Resources']) && in_array($data['Resources'][$banner_resource->id_web_resource]->type,$var_type))
                                                                                <section class="panel panel-primary" id="panel-1" data-portlet-item>
                                                                                        <header class="portlet-handler">
                                                                                            <div class="checkbox-custom checkbox-default">
                                                                                            <input name="resources[]" value="{{$banner_resource->id_web_resource}}" type="checkbox" checked="checked" id="checkbox{{$banner_resource->id_web_resource}}">
                                                                                            <label for="checkbox{{$banner_resource->id_web_resource}}">{{$data['Resources'][$banner_resource->id_web_resource]->title}}</label>
                                                                                            </div>
                                                                                        </header>
                                                                                </section>
                                                                            @endif
                                                                       @endforeach
                                                                         @foreach($data['Resources'] as $value)
                                                                            @if(!in_array($value->id_web_resource,$data['resourcechecked']) && in_array($value->type,$var_type))
                                                                                <section class="panel panel-primary" id="panel-1" data-portlet-item>
                                                                                        <header class="portlet-handler">
                                                                                            <div class="checkbox-custom checkbox-default">
                                                                                           <input name="resources[]" value="{{$value->id_web_resource}}" type="checkbox"  id="checkbox{{$value->id_web_resource}}">
                                                                                                <label for="checkbox{{$value->id_web_resource}}">{{$value->title}}</label>
                                                                                            </div>
                                                                                        </header>
                                                                                </section>
                                                                            @endif
                                                                        @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                </div>

                                </section>
                             </div>
                            <div class="col-md-2">
                                <section class="panel">


                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                       <div class="checkbox-custom checkbox-default">
                                                                                <input name="enabled" type="checkbox"  <?= (!empty($data['infBanner']->enabled) && $data['infBanner']->enabled == 1)? 'checked' : '' ; ?> id="checkboxActiavted">
                                                                                <label for="checkboxActiavted">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                                        </div>
                                                                </div>
                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_banner">{{ trans('admin-app.title.save') }}</button>
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>

                                                </div>

                                </section>
                            </div>

            </div>

</section>
<script>
  /*$( function() {
    $( "#sortable" ).sortable({
      placeholder: "ui-state-highlight"
    });
    $( "#sortable" ).disableSelection();
  } );*/
  </script>
@stop
