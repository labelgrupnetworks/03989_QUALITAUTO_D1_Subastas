@extends('admin::layouts.logged')
@section('content')

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
    
	<div id="cms">
        <h1>Configuración</h1>
        <br>
            <div class="row mb-10">
                <div class="col-md-12">
                    <button id="save_registration_disabled" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right">{{ trans('admin-app.title.save') }}</button>
                </div>
            </div>
            <section class="panel">
               
                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                
                                                <tbody>
                                                    
                                                        <tr role="row" class="odd">
                                                            <td style="width: 75%">Deshabilitar registro</td>
                                                            <td class="text-center">
                                                                <select class="form-control" id="select_registration">
                                                                    @if($data['registration_disabled'])
                                                                    <option value="1">Si</option> 
                                                                    <option value="0" selected>No</option>
                                                                    @else
                                                                    <option value="1" selected>Si</option> 
                                                                    <option value="0">No</option>
                                                                    @endif
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
            </section>
	</div>	
</section>




@stop
