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

	<div id="cms">
            <div class="row mb-10">
                <div class="col-md-12">
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right save_traducciones" >{{ trans('admin-app.title.save') }}</button>
                </div>
            </div>
            <section class="panel">
                <form id="traducciones">
                    <input type='hidden' value='{{$data['lang']}}' name='lang'>
                    @if(!empty(head($data['ES'])->key_header))
                     <input type='hidden' value='{{head($data['ES'])->key_header}}' name='key_header'>
                    @endif
                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">                                                    
                                                <tbody>
                                                    @foreach($data[$data['lang']] as $key => $trad)
                                                        <tr role="row" class="odd">
                                                            <td style="width: 100%; ">
                                                                <p style="word-break: break-all;">
                                                                <span>{{$data['ES'][$key]->key_translate}}</span> - <span style='font-size: 14px;    word-break: break-all;'>{{$data['ES'][$key]->web_translation}}</span>
                                                                 <input type="text" name="{{$data['ES'][$key]->id_key_translate}}" class="form-control" value="{{$trad->web_translation}}">
                                                                </p>
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
            <?php 
                $traducciones = new \App\Models\Translate;
                $trans = $traducciones->headersTrans();
            ?>
            @if(!empty($_GET) && $_GET['admin'] == 'superadmin')
            <form id='new_traduction'>
            <section class="panel">
               <div class="panel-body">
                <div class="row mb-10">
                 <div class="col-md-3  mb-10">
                     <select name='key_headers' class="form-control">
                         @foreach($trans as $traduc)
                            <option <?= ($traduc->key_header=$data['key'])?'selected':'';?>value="{{$traduc->key_header}}">{{$traduc->key_header}}</option>
                         @endforeach
                     </select>
	</div>	
                    <div class="col-md-9  mb-10">
                        <input type='text' name='key_translate' class="form-control" placeholder="key">
                 </div>
                    <div class="col-md-3">
                   <select name='lang' class="form-control">
                        <option value="ES">Español</option>
                       <option value="EN">Ingles</option>
                   </select>
                 </div>
                 <div class="col-md-9  mb-10">
                    <input type='text' name='web_translation' class="form-control" placeholder="Texto">
                 </div>
                 
               </div>
                   <div class="row mb-10">
                <div class="col-md-12">
                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right new_traducciones" >{{ trans('admin-app.title.save') }}</button>
                </div>
            </div>
               </div>
</section>
            </form>
            @endif
	</div>	
</section>

@stop
