@extends('admin::layouts.logged')
@section('content')
{{ trans(\Config::get('app.theme').'-app.foot.faq') }}
<?php
	$cbs=isset($_GET['cbs'])?$_GET['cbs'] : '';
	$see =  isset($_GET['see'])?$_GET['see'] : 'B' ;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="/admin">
						<i class="fa fa-home"></i>
					</a>
				</li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
			</div>
	</header>

	<div id="faqs">
			
		<section class="panel">
			<div class="panel-body">
				<div class="col-xs-8">
					<h1>Faqs</h1>
					<br>
				</div>

                            <div class="clearfix"></div>



                            <div id="contenedor">
				@if (isset($data['catsU']) && !empty($data['catsU']))
					@foreach ($data['catsU'] as $kf => $vf) 

						<div class="row sortable" style="border:#CCC 1px solid;background:#DDD;margin:15px 15px 0 15px;padding:10px;">
							<div class="col-xs-10" style="font-size:18px;padding-top:5px;">{{ $vf['info']->nombre_faqcat}}</div>
							<div class="col-xs-2 text-right">
								<a href="/admin/faqs/categories/edit/{{ $vf['info']->cod_faqcat }}" class="btn btn-default"><span class="fa fa-edit"></span></a>
								@if (count($vf['items'])==0)
									<a href="javascript:deleteFaqCat({{ $vf['info']->cod_faqcat }})" class="btn btn-default"><span class="fa fa-close"></span></a>
								@endif
							</div>
						</div>
							
						@foreach ($vf['items'] as $k => $v)
							<div class="row" style="border:#CCC 1px solid;background:#EEE;margin:0 15px;padding:10px;cursor:pointer;" onclick='javascript:$("#plega{{ $k }}").toggle();'">

								<div class="col-xs-10" style="padding:8px 0 0 15px">
									<big>{{ $v->nombre_faqcat}}&nbsp;&nbsp; ( <?php if (isset($data['faqs'][$k])) echo count($data['faqs'][$k]); else echo "0";?> )</big>
								</div>
								<div class="col-xs-2 text-right">
									@if ($v->parent_faqcat)
										<a href="/admin/faqs/edit?cat={{ $v->cod_faqcat }}" class="btn btn-default">Nueva faq</a>
									@endif
										<a href="/admin/faqs/categories/edit/{{ $v->cod_faqcat }}" class="btn btn-default"><span class="fa fa-edit"></span></a>
									@if (!isset($data['faqs'][$k]) || count($data['faqs'][$k])==0)
										<a href="javascript:deleteFaqCat({{ $v->cod_faqcat }})" class="btn btn-default"><span class="fa fa-close"></span></a>
									@endif
								</div>
							</div>

							<div class="row" style="border:#CCC 1px solid;margin:0 15px;display:none" id="plega{{ $k }}">
							@if (isset($data['faqs'][$k]))

								@foreach ($data['faqs'][$k] as $item)

									<div style="border-bottom:#CCC 1px solid;">
										<div class="col-xs-9" style="padding:15px">
											{{ $item->titulo_faq }}
										</div>
										<div class="col-xs-3 text-right" style="padding:10px">
											<a href="/admin/faqs/edit/{{ $item->cod_faq }}" class="btn btn-primary"><span class="fa fa-edit"></span></a>
											<a href="javascript:deleteFaq({{ $item->cod_faq }})" class="btn btn-danger"><span class="fa fa-close"></span></a>
										</div>
										<div class="clearfix"></div>
									</div>

								@endforeach

							@endif
							</div>
						@endforeach
						<br>
					@endforeach
					
				@endif
                            </div>
                            
                          
                          
				<br><br>

				<h3>Nueva categoría</h3>

				<form name="newCat" id="newCat" action="javascript:newFaqCat();">
				<table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">

					<tr>
						<th>{{trans(\Config::get('app.theme').'-app.faq.newCategory_name') }}</th>
						<th>{{trans(\Config::get('app.theme').'-app.faq.newCategory_parent') }}</th>
						<th></th>
					</tr>
					
					<tr>
						<td>
							<input value="" name="new" id="new" class="form-control input-lg">
						</td>
						<td>
							<select name="parent" id="parent" class="form-control input-lg">
								<option value="0">Ninguno</option>
								@foreach ($data['catsU'] as $kk => $vv)
									<option value="{{ $vv['info']->cod_faqcat }}">
										{{ $vv['info']->nombre_faqcat }}
									</option>
								@endforeach
							</select>
						</td>
						<td><input type="submit" class="btn btn-primary"></td>
					</tr>

				</table>
				</form>




			</div>
		</section>
	</div>	
</section>

@stop
