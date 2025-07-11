@extends('admin::layouts.logged')
@section('content')
<?php
    $cbs=isset($_GET['cbs'])?$_GET['cbs'] : '';
    $see =  isset($_GET['see'])?$_GET['see'] : 'B' ;
?>
<section role="main" class="content-body">

	<div id="cms">
            @if($see == 'B' )
                <div class="row mb-10">
                    <div class="col-md-12"> <span style='font-size:24px'><strong>{{$data['banner_section_name']}}</strong></span>
                        <a href="banner/name?cbs={{$cbs}}&see={{$see}}" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right">{{ trans('admin-app.title.created') }}</a>
                    </div>
                </div>
            @endif
            <section class="panel">

                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">

                                                <tbody>
                                                    @foreach($data['inf'] as $banner)
                                                    <?php
                                                        $url_banner= "banner/name/".$banner->id_web_banner."?cbs=$cbs&see=$see";
                                                    ?>
                                                        <tr role="row" class="odd">
                                                            <td style="width: 90%; ">
                                                                <a class="" href="{{$url_banner}}">
                                                                {{$banner->title}}
                                                                 </a>
                                                                 ({{$banner->key_name}})

                                                            </td>
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="{{$url_banner}}">{{ trans('admin-app.title.edit') }}</td>
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
