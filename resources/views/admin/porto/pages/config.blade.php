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

		<div id="config">
                    <form id='form_config' >
                        <div class="row mb-10">
                            <div class="col-md-12">
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right save_config" >{{ trans('admin-app.title.save') }}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                            <div class="panel-actions">
                                                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            </div>

                                            <h2 class="panel-title">{{ trans('admin-app.title.config_general') }}</h2>
                                    </header>
                                    <div class="panel-body">
                                         @if(!in_array('name',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('name',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.name') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.name') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('name',$data['config_pago']))? 'name="name"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['name']))? '':$data['web_config']['name'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.name_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('home_enable_services',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('home_enable_services',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.home_enable_services') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.home_enable_services') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('home_enable_services',$data['config_pago']))? 'name="home_enable_services"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['home_enable_services']) || $data['web_config']['home_enable_services'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['home_enable_services']) && $data['web_config']['home_enable_services'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                    </select>
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.home_enable_services_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('google_analytics',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('google_analytics',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.google_analytics') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.google_analytics') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <input <?= (!in_array('google_analytics',$data['config_pago']))? 'name="google_analytics"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['google_analytics']))? '':$data['web_config']['google_analytics'] ?>' /> 

                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.google_analytics_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('home_enable_big_buttons',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('home_enable_big_buttons',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.home_enable_big_buttons') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.home_enable_big_buttons') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('home_enable_big_buttons',$data['config_pago']))? 'name="home_enable_big_buttons"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['home_enable_big_buttons']) || $data['web_config']['home_enable_big_buttons'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['home_enable_big_buttons']) && $data['web_config']['home_enable_big_buttons'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                    </select>                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.home_enable_big_buttons_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('home_enable_big_buttons',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('home_enable_big_buttons',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.home_enable_big_buttons') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.home_enable_big_buttons') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('home_enable_big_buttons',$data['config_pago']))? 'name="home_enable_big_buttons"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['home_enable_big_buttons']) || $data['web_config']['home_enable_big_buttons'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['home_enable_big_buttons']) && $data['web_config']['home_enable_big_buttons'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                    </select>                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.home_enable_big_buttons_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!in_array('home_enable_featured_lots',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('home_enable_featured_lots',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.home_enable_featured_lots') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.home_enable_featured_lots') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('home_enable_featured_lots',$data['config_pago']))? 'name="home_enable_featured_lots"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['home_enable_featured_lots']) || $data['web_config']['home_enable_featured_lots'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['home_enable_featured_lots']) && $data['web_config']['home_enable_featured_lots'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                    </select>                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.home_enable_featured_lots_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if(!in_array('delivery_address',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('delivery_address',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.delivery_address') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.delivery_address') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('delivery_address',$data['config_pago']))? 'name="delivery_address"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['delivery_address']) || $data['web_config']['delivery_address'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['delivery_address']) && $data['web_config']['delivery_address'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.delivery_address_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_interest_tsec',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_interest_tsec',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_interest_tsec') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_interest_tsec') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_interest_tsec',$data['config_pago']))? 'name="enable_interest_tsec"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_interest_tsec']) || $data['web_config']['enable_interest_tsec'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_interest_tsec']) && $data['web_config']['enable_interest_tsec'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_interest_tsec_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('home_enable_historic',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('home_enable_historic',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.home_enable_historic_desc') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.home_enable_historic') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('home_enable_historic',$data['config_pago']))? 'name="home_enable_historic"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['home_enable_historic']) || $data['web_config']['home_enable_historic'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['home_enable_historic']) && $data['web_config']['home_enable_historic'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.home_enable_historic_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_cache',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_cache',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_cache') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_cache') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_cache',$data['config_pago']))? 'name="enable_cache"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_cache']) || $data['web_config']['enable_cache'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_cache']) && $data['web_config']['enable_cache'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_cache_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('session_timeout',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('session_timeout',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.session_timeout') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.session_timeout') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('session_timeout',$data['config_pago']))? 'name="session_timeout"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['session_timeout']))? '':$data['web_config']['session_timeout'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.session_timeout_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('distance_to_play_favs',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('distance_to_play_favs',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.distance_to_play_favs') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.distance_to_play_favs') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <input <?= (!in_array('distance_to_play_favs',$data['config_pago']))? 'name="distance_to_play_favs"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['distance_to_play_favs']))? '':$data['web_config']['distance_to_play_favs'] ?>' /> 
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.distance_to_play_favs_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('fb_app_id',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('fb_app_id',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.fb_app_id') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.fb_app_id') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('fb_app_id',$data['config_pago']))? 'name="fb_app_id"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['fb_app_id']))? '':$data['web_config']['fb_app_id'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.fb_app_id_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('dummy_bidder',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('dummy_bidder',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.dummy_bidder') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.dummy_bidder') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('dummy_bidder',$data['config_pago']))? 'name="dummy_bidder"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['dummy_bidder']))? '':$data['web_config']['dummy_bidder'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.dummy_bidder_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('regtype',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('regtype',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.regtype') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.regtype') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('regtype',$data['config_pago']))? 'name="regtype"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['regtype']))? '':$data['web_config']['regtype'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.regtype_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('fpag_default',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('fpag_default',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.fpag_default') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.fpag_default') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('fpag_default',$data['config_pago']))? 'name="fpag_default"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (!empty($data['web_config']['fpag_default']))? '':$data['web_config']['fpag_default'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.fpag_default_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('icon_multiple_images',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('icon_multiple_images',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.icon_multiple_images') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.icon_multiple_images') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('icon_multiple_images',$data['config_pago']))? 'name="icon_multiple_images"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['icon_multiple_images']) || $data['web_config']['icon_multiple_images'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['icon_multiple_images']) && $data['web_config']['icon_multiple_images'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.icon_multiple_images_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                            <div class="panel-actions">
                                                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            </div>

                                            <h2 class="panel-title">{{ trans('admin-app.title.config_subasta') }}</h2>
                                    </header>
                                    <div class="panel-body">
                                        @if(!in_array('enable_direct_sale_auctions',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_direct_sale_auctions',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_direct_sale_auctions') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_direct_sale_auctions') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_direct_sale_auctions',$data['config_pago']))? 'name="enable_direct_sale_auctions"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_direct_sale_auctions']) || $data['web_config']['enable_direct_sale_auctions'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_direct_sale_auctions']) && $data['web_config']['enable_direct_sale_auctions'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_direct_sale_auctions_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_historic_auctions',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_historic_auctions',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_historic_auctions') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_historic_auctions') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_historic_auctions',$data['config_pago']))? 'name="enable_historic_auctions"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_historic_auctions']) || $data['web_config']['enable_historic_auctions'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_historic_auctions']) && $data['web_config']['enable_historic_auctions'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_historic_auctions_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('auto_licit',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('auto_licit',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.auto_licit') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.auto_licit') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <select class="form-control" <?= (!in_array('auto_licit',$data['config_pago']))? 'name="auto_licit"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['auto_licit']) || $data['web_config']['auto_licit'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['auto_licit']) && $data['web_config']['auto_licit'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.auto_licit_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_general_auctions',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_general_auctions',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_general_auctions') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_general_auctions') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_general_auctions',$data['config_pago']))? 'name="enable_general_auctions"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_general_auctions']) || $data['web_config']['enable_general_auctions'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_general_auctions']) && $data['web_config']['enable_general_auctions'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_general_auctions_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_tr_auctions',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_tr_auctions',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_tr_auctions') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_tr_auctions') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_tr_auctions',$data['config_pago']))? 'name="enable_tr_auctions"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_tr_auctions']) || $data['web_config']['enable_tr_auctions'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_tr_auctions']) && $data['web_config']['enable_tr_auctions'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_tr_auctions_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                         @if(!in_array('pujas_maximas_mostradas',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('pujas_maximas_mostradas',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.pujas_maximas_mostradas') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.pujas_maximas_mostradas') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <input <?= (!in_array('pujas_maximas_mostradas',$data['config_pago']))? 'name="pujas_maximas_mostradas"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['pujas_maximas_mostradas']))? '':$data['web_config']['pujas_maximas_mostradas'] ?>' /> 

                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.pujas_maximas_mostradas_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('cd_time',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('cd_time',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.cd_time') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.cd_time') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('cd_time',$data['config_pago']))? 'name="cd_time"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['cd_time']))? '':$data['web_config']['cd_time'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.cd_time_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                    </div>
                                </section>
                            </div>
                        </div>
                        
                         <div class="row">
                            <div class="col-md-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                            <div class="panel-actions">
                                                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            </div>

                                            <h2 class="panel-title">{{ trans('admin-app.title.config_electr') }}</h2>
                                    </header>
                                    <div class="panel-body">
                                        @if(!in_array('debug_to_email',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('debug_to_email',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.debug_to_email') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.debug_to_email') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('debug_to_email',$data['config_pago']))? 'name="debug_to_email"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['debug_to_email']))? '':$data['web_config']['debug_to_email'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.debug_to_email_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                         @if(!in_array('admin_email',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('admin_email',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.admin_email') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.admin_email') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('admin_email',$data['config_pago']))? 'name="admin_email"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['admin_email']))? '':$data['web_config']['admin_email'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.admin_email_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('from_email',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('from_email',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.from_email') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.from_email') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('from_email',$data['config_pago']))? 'name="from_email"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['from_email']))? '':$data['web_config']['from_email'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.from_email_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_emails',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_emails',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_emails') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_emails') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_emails',$data['config_pago']))? 'name="enable_emails"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_emails']) || $data['web_config']['enable_emails'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_emails']) && $data['web_config']['enable_emails'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_emails_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('copies_emails_mailbox',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('copies_emails_mailbox',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.copies_emails_mailbox') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.copies_emails_mailbox') }} </p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <input <?= (!in_array('copies_emails_mailbox',$data['config_pago']))? 'name="copies_emails_mailbox"' : 'disabled ' ?>  class="form-control" type='text' value='<?=  (empty($data['web_config']['copies_emails_mailbox']))? '':$data['web_config']['copies_emails_mailbox'] ?>' /> 
                                                </div>
                                                    <div class="col-md-4">
                                                        <p>{{ trans('admin-app.config.copies_emails_mailbox_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('copies_emails',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('copies_emails',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.copies_emails') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.copies_emails') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('copies_emails',$data['config_pago']))? 'name="copies_emails"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['copies_emails']) || $data['web_config']['copies_emails'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['copies_emails']) && $data['web_config']['copies_emails'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.copies_emails_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_email_bid',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_email_bid',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_email_bid') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_email_bid') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_email_bid',$data['config_pago']))? 'name="enable_email_bid"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_email_bid']) || $data['web_config']['enable_email_bid'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_email_bid']) && $data['web_config']['enable_email_bid'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_email_bid_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('enable_email_overbid',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('enable_email_overbid',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.enable_email_overbid') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.enable_email_overbid') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('enable_email_overbid',$data['config_pago']))? 'name="enable_email_overbid"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['enable_email_overbid']) || $data['web_config']['enable_email_overbid'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['enable_email_overbid']) && $data['web_config']['enable_email_overbid'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.enable_email_overbid_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        
                                    </div>
                                </section>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <section class="panel">
                                    <header class="panel-heading">
                                            <div class="panel-actions">
                                                    <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                                            </div>

                                            <h2 class="panel-title">{{ trans('admin-app.title.config_temp_real') }}</h2>
                                    </header>
                                    <div class="panel-body">
                                       @if(!in_array('tr_show_chat',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_chat',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_chat') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_chat') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_chat',$data['config_pago']))? 'name="tr_show_chat"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_chat']) || $data['web_config']['tr_show_chat'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_chat']) && $data['web_config']['tr_show_chat'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_chat_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_pujas',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_pujas',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_pujas') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_pujas') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_pujas',$data['config_pago']))? 'name="tr_show_pujas"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_pujas']) || $data['web_config']['tr_show_pujas'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_pujas']) && $data['web_config']['tr_show_pujas'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_pujas_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_video',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_video',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_video') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_video') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_video',$data['config_pago']))? 'name="tr_show_video"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_video']) || $data['web_config']['tr_show_video'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_video']) && $data['web_config']['tr_show_video'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_video_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_buscador',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2">
                                                    @if(!in_array('tr_show_buscador_desc',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_buscador') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_buscador') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_buscador',$data['config_pago']))? 'name="tr_show_buscador"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_buscador']) || $data['web_config']['tr_show_buscador'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_buscador']) && $data['web_config']['tr_show_buscador'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_buscador_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_adjudicaciones',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_adjudicaciones',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_adjudicaciones') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_adjudicaciones') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_adjudicaciones',$data['config_pago']))? 'name="tr_show_adjudicaciones"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_adjudicaciones']) || $data['web_config']['tr_show_adjudicaciones'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_adjudicaciones']) && $data['web_config']['tr_show_adjudicaciones'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_adjudicaciones_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_info',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_info',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_info') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_info') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_info',$data['config_pago']))? 'name="tr_show_info"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_info']) || $data['web_config']['tr_show_info'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_info']) && $data['web_config']['tr_show_info'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_info_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_aslot',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_aslot',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_aslot') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_aslot') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_aslot',$data['config_pago']))? 'name="tr_show_aslot"' : 'disabled' ?>>
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_aslot']) || $data['web_config']['tr_show_aslot'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_aslot']) && $data['web_config']['tr_show_aslot'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_aslot_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if(!in_array('tr_show_ordenes_licitacion',$data['fields_hidder']))
                                            <div class="row mb-10">
                                                <div class="col-md-2"> 
                                                    @if(!in_array('tr_show_ordenes_licitacion',$data['config_pago']))
                                                        <p> {{ trans('admin-app.config.tr_show_ordenes_licitacion') }} </p>
                                                    @else
                                                        <p class="InfoPay msg_box" msg="{{ trans('admin-app.config.no_activada') }}" title="{{ trans('admin-app.config.title_no_activada') }}" type="error" > {{ trans('admin-app.config.tr_show_ordenes_licitacion') }}</p>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <select class="form-control" <?= (!in_array('tr_show_ordenes_licitacion',$data['config_pago']))? 'name="tr_show_ordenes_licitacion"' : 'disabled' ?> >
                                                        <option value="0" <?= (empty($data['web_config']['tr_show_ordenes_licitacion']) || $data['web_config']['tr_show_ordenes_licitacion'] == 0)? 'selected' : '';  ?>>{{ trans('admin-app.config.no') }}</option> 
                                                        <option value="1" <?= (!empty($data['web_config']['tr_show_ordenes_licitacion']) && $data['web_config']['tr_show_ordenes_licitacion'] == 1)? 'selected' : '';  ?>>{{ trans('admin-app.config.si') }}</option>                                
                                                  </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <p>{{ trans('admin-app.config.tr_show_ordenes_licitacion_desc') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-md-12">
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary pull-right save_config" >{{ trans('admin-app.title.save') }}</button>
                            </div>
                        </div>
                     </form>
		</div>	
		</div>
            </section>

@stop
