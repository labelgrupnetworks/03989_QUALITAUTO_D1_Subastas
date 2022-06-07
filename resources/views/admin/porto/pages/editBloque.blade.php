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
            <div class="row">
                
                        <form id="edit_bloque">
                             <div class="col-md-10">
                                <section class="panel">
                                        
                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-4">
                                                                        
                                                                   
                                                                        <input type="text" name="title" placeholder="{{ trans('admin-app.placeholder.titulo') }}" class="form-control" value="{{empty($bloque->title)? '' : $bloque->title}}">
                                                                   
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    
                                                                         <input type="text" name="key_name" placeholder="{{ trans('admin-app.placeholder.key') }}" class="form-control" value="{{ empty($bloque->key_name)? '' : $bloque->key_name}}">
                                                                    
                                                                        
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    
                                                                         <input type="text" name="cache" placeholder="{{ trans('admin-app.placeholder.cache') }}" class="form-control" value="{{ empty($bloque->time_cache)? '' : $bloque->time_cache}}">
                                                                    
                                                                        
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                                

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                                
                                                                       
                                                        </div>
                                                        <div class="row">
                                                                <div class="col-lg-12">
                                                                   
                                                                         <textarea style="height: 499px;" name="consulta" placeholder="{{ trans('admin-app.placeholder.consulta') }}" class="form-control" rows="5">{{empty($bloque)? '' : $bloque->products}}</textarea>
                                                                    
                                                                </div>
                                                        </div>
                                                </div>
                                               
                                </section>
                             </div>
                            <div class="col-md-2">
                                <section>

                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                       <div class="checkbox-custom checkbox-default">
                                                                                <input name="enabled" type="checkbox" <?= (empty($bloque->enabled) || $bloque->enabled == 0)? '' : 'checked'; ?>  id="checkboxExample3">
                                                                                <label for="checkboxExample3">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                                        </div>
                                                                </div>
                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_bloque">{{ trans('admin-app.title.save') }}</button>
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                     
                                                            <input type="hidden" class="id_input" name="id" value="{{empty($bloque)? '0' : $bloque->id_web_block}}" >
                                                    
                                                    <input type="hidden" name="type" value="S">
                                                        
                                                </div>
                                                
                                </section>
                            </div>
                        </form>
                 
        </div>
           
	</div>	
</section>

@stop
