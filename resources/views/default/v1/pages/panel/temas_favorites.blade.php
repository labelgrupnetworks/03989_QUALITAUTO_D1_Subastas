@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

<div class="color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
            <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
            </div>
        </div>
    </div>
</div>






<div class="container hide">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
		</div>
	</div>
</div>
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">

		<div class="">
        <?php $tab="temas-favorites"; ?>
	@include('pages.panel.menu_micuenta')
		  <!-- Tab panes -->
            <form id="save_panel_favs">
                {{ csrf_field() }}
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active tabe-cust" id="uno">
          <div class="row">
                            @foreach($data['favorites'] as $favorites)
                           <div class="col-xs-6 col-sm-3 ">
                                   <div class="item_interest_link  add_intereses" title="{{$favorites->des_tsec}}">
                                        <label style="display:inline!important;">


                                           <div class="item_interest">
                                                   <div class="title_item">
                                                           {{ucfirst($favorites->des_tsec)}}
                                                   </div>
                                                   <?php $fa = true ?>
                                                  @foreach ($data['fav'] as $fav)
                                                    @if($fav->tsec_cliwebtsec == $favorites->cod_tsec)
                                                         <i id="{{$favorites->des_tsec}}" class="fa fa-heart"></i>
                                                        <input class="hidden" checked type="checkbox" name="interest_{{$favorites->cod_tsec}}">
                                                          <?php $fa = false ?>
                                                    @endif

                                                   @endforeach
                                                   @if($fa)
                                                        <i id="{{$favorites->des_tsec}}" class="fa fa-heart-o"></i>
                                                        <input class="hidden"  type="checkbox" name="interest_{{$favorites->cod_tsec}}">
                                                   @endif

                                           </div>
                                        </label>
                                   </div>

                           </div>
                           @endforeach
                            <div class="col-xs-12 col-sm-12 text-center">
                                  <button type="button" id="save_panel_favorites" class="btn btn-step-reg">{{ trans(\Config::get('app.theme').'-app.user_panel.save') }}</button>
                           </div>
          </div>
		    </div>

		  </div>
            </form>
		</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div id="myFavorites" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"> <img src="/img/icons/shape.png"</button>
      </div>
      <div class="modal-body">
        <h1 class="titlecat" style="text-align: center;">{{ trans(\Config::get('app.theme').'-app.user_panel.success_save') }}</h1>
      </div>
    </div>

  </div>
</div>
@stop
