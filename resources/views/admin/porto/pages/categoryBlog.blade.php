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
            <div class="row mb-10">
                <div class="col-md-12">
                    <a href="category-blog/name/" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right">{{ trans('admin-app.title.created') }}</a>
                </div>
            </div>
            <section class="panel">
               
                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                                
                                                <tbody>
                                                    @foreach($data as $blog)
                                                        <tr role="row" class="odd">
                                                            <td style="width: 90%">{{$blog->title_category_blog}}</td>
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="category-blog/name/<?= $blog->id_category_blog ?> ">{{ trans('admin-app.title.edit') }}</td>
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

@stop
