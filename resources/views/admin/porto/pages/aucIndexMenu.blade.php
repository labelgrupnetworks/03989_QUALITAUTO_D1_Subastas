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
            <section class="panel">
                <div class="panel-body">
                        <div class="col-md-12">
                                <section class="panel">
                                        <div class="panel-body">
                                                <div class="col-md-6">
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
                                </section>
                        </div>
                </div>
            </section>
	</div>	
</section>


@stop
