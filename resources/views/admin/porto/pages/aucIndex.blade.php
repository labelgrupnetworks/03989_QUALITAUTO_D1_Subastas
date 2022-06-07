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
                    <a href="auc-index/name/" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right">{{ trans('admin-app.title.created') }}</a>
                </div>
            </div>
            <div class="row">
                    <div class="col-md-12">
                            <div class="tabs tabs-bottom tabs-primary">
                                    <ul class="nav nav-tabs nav-justified">
                                            <li class="active">
                                                    <a href="#popular8" data-toggle="tab" class="text-center"> {{ trans('admin-app.title.menu') }}</a>
                                            </li>
                                            <li>
                                                    <a href="#recent8" data-toggle="tab" class="text-center">{{ trans('admin-app.title.order-menu') }}</a>
                                            </li>
                                    </ul>
                                    <div class="tab-content">
                                            <div id="popular8" class="tab-pane active">
                                                    <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                                        <div class="">
                                                                <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info"> 
                                                                        <tbody>
                                                                            @foreach($data['Secciones'] as $auc)
                                                                                @if( $auc->type != 'P' )
                                                                                   <tr role="row" class="odd">
                                                                                       <td style="width: 85%; ">
                                                                                           <a class="" href="auc-index/name/<?= $auc->id_web_auc_index ?> " <?= ($auc->parent != '0')?'style="margin-left:30px;"':''; ?>> 
                                                                                           {{$auc->title}}
                                                                                            </a>
                                                                                           <span>
                                                                                               @if($auc->type == 'S')
                                                                                                   {{ trans('admin-app.selected.session') }}
                                                                                               @elseif($auc->type == 'P')
                                                                                                   {{ trans('admin-app.selected.padre') }}
                                                                                               @else
                                                                                                   {{ trans('admin-app.selected.familia') }}
                                                                                               @endif
                                                                                           </span>

                                                                                       </td>
                                                                                       <td class="text-center">
                                                                                           @if($auc->enabled == '1')
                                                                                               <i class="fa fa-eye" aria-hidden="true"></i>
                                                                                           @else
                                                                                               <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                                                           @endif</td>
                                                                                       <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="auc-index/name/<?= $auc->id_web_auc_index ?> ">{{ trans('admin-app.title.edit') }}</a></td>
                                                                                   </tr>
                                                                                @endif   
                                                                            @endforeach
                                                                        </tbody>
                                                                </table>
                                                        </div>
                                                </div>
                                            </div>
                                            <div id="recent8" class="tab-pane">
                                                     <div id="nestable-menu" class="dd" >
                                                                <ol class="dd-list">
                                                                    @foreach($data['Parent'] as $auc)
                                                                        <li class="dd-item <?php if($auc->enabled == 0){echo 'hidden';}else{echo ' ';} ?>" data-id="{{ $auc->id_web_auc_index }}">
                                                                                <div class="dd-handle">{{ $auc->title }} </div>
                                                                                @if( $auc->type == 'P' )
                                                                                <ol class="dd-list">
                                                                                    @foreach($data['FamiliaSession'] as $familia)
                                                                                        @if( $familia->parent == $auc->id_web_auc_index )
                                                                                            <li class="dd-item <?php if($familia->enabled == 0){echo 'hidden';}else{echo ' ';} ?>" data-id="{{$familia->id_web_auc_index}}">
                                                                                                <div class="dd-handle">{{ $familia->title }}</div>
                                                                                            </li>
                                                                                        @endif
                                                                                     @endforeach
                                                                                </ol>
                                                                                @endif
                                                                        </li>
                                                                        @endforeach
                                                                        
                                                                </ol>
                                                        </div>
                                            </div>
                                    </div>

                            </div>
                    </div>
            </div>
	</div>	
</section>

@stop
