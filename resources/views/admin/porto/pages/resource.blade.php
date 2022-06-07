@extends('admin::layouts.logged')
@section('content')
<?php
    $crs=isset($_GET['crs'])?$_GET['crs'] : '';
?>
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
                    <a href="/admin/resources/name?see=<?= $_GET["see"]?>&crs={{$crs}}" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right">{{ trans('admin-app.title.created') }}</a>
                </div>
            </div>
            <section class="panel">
                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                
                                                <tbody>
                                                    @foreach($data['inf'] as $bloque)
                                                        <tr role="row" class="odd">
                                                            <td style="width: 90%">
                                                                <a  href="/admin/resources/name/<?= $bloque->id_web_resource ?>?&crs={{$crs}} ">
                                                                {{$bloque->title}}
                                                                </a>
                                                            </td>
                                                            
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="resources/name/<?= $bloque->id_web_resource ?>?&crs={{$crs}}">{{ trans('admin-app.title.edit') }}</td>
                                                            <td class="text-center">
                                                            @if(empty($bloque->id_web_resource_banner))
                                                                <a class="delete_resource mb-xs mt-xs mr-xs btn btn-danger modal-basic" data-id="<?= $bloque->id_web_resource ?>"  data-toggle="modal" data-target="#delete" >{{ trans('admin-app.title.delete') }}
                                                            @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
            </section>
	</div>	
</section>


<!-- Modal -->
<div id="delete" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <h4>{{ trans('admin-app.title.sure_delete') }}</h4>
      </div>
      <div class="modal-footer">
            <button id="delete_resource" class="btn btn-danger modal-dismiss">Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@stop
