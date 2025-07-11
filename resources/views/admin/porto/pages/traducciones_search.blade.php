@extends('admin::layouts.logged')
@section('content')
<section role="main" class="content-body">
	<div id="cms">
            <div class="row mb-10">
                <div class="col-md-12">
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right save_traducciones" >{{ trans('admin-app.title.save') }}</button>
                </div>
            </div>
            <?php
                $idiomes = \Config::get('app.locales');
                $lang_select= '';
                if(!empty($_GET["lang"])){
                    $lang_select = $_GET["lang"];
                }
            ?>
            <section class="panel">
              <form action="/admin/traducciones/search" method="get" class='panel-body'>
                <div class="col-md-3">
                  <select name='lang' class="form-control">
                   @foreach($idiomes as $key => $lang)
                       <option <?= strtoupper($key) == $lang_select ?'selected':'';?> value="{{strtoupper($key)}}">{{$lang}}</option>
                   @endforeach
                  </select>
                </div>
                <div class="col-md-7  mb-10">
                   <input type='text' name='web_translation' class="form-control" placeholder="Texto">
                </div>
                <div class="col-md-2  mb-10">
                  <p><input class="btn btn-default" type="submit" /></p>
                </div>
               </form>
            </section>
             @if(!empty($_GET["lang"]) && !empty($_GET["web_translation"]))
            <section class="panel">
                <form id="traducciones">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type='hidden' value='{{$data['lang']}}' name='lang'>
                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                <tbody>
                                                    @foreach($data['trad'] as $key_header => $value_headers)
                                                        @foreach($value_headers as $key_translate => $trad)
                                                        <tr role="row" class="odd">
                                                            <td style="width: 100%; ">

                                                                 <span>{{$trad->key_header}} - {{$trad->key_translate}}</span> / <span style='font-size: 14px;'>{{$trad->web_translation}}</span>

                                                                @if(!empty($trad->id_key))
                                                                <input type="text" name="{{$trad->key_header}}**{{$trad->key_translate}}" class="form-control" value="{{$trad->web_translation}}">
                                                                @else
                                                                 <input type="text" name="{{$trad->key_header}}**{{$trad->key_translate}}" class="form-control" value="">
                                                                 @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
            </form>
            </section>
             @endif
	</div>
</section>

@stop
