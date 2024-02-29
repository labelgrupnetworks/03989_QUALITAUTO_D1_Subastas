@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.foot.featured_auctions') }}
@stop


@section('content')



    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center">
					@php
					$bread = array();

					$bread[] = array( "name" =>trans($theme.'-app.foot.featured_auctions') );
					@endphp
					
					@include('includes.breadcrumb')
                    <?php //Si quieren mostrar nombre de la subasta o que se vea texto Lotes ?>

                        <h1 class=" color-letter text-center">{{ trans($theme.'-app.foot.featured_auctions') }}</h1>
						@if(count($sessions)>0)
							<h2 class=" color-letter text-center">{{ head($sessions)[0]->des_sub  }}</h2>
						@endif



                </div>
            </div>
        </div>

	<div class="container">
		<div class="row">
			@foreach ($sessions as  $name => $session)
			<div class="col-xs-offset-4 col-xs-4  session-remates-destacados text-center mb-3"> <span > {{ $name  }} </span> </div>
				@foreach ($session as  $lot)
					@include('includes.remates_destacados')
				@endforeach
			@endforeach
		</div>
	</div>
@stop

