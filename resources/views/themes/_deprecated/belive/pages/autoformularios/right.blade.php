@extends('layouts.default')

@section('title')
	{{ $data['title'] }}
@stop

@section('content')
<?php 
	$bread[] = array("name" => $data['title']  );
?>

<script src="https://www.google.com/recaptcha/api.js?hl={{ \Config::get('app.locale') }}" async defer></script>



<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			
			<h1 class="titlePage"> {{ $data['title'] }}</h1>
			@include('includes.breadcrumb')
			
		</div>
	</div>


	<div class="row">

	   <div class="col-xs-12 col-md-7">
			<div class="row form-group">
				<form name="autoformulario" id="autoformulario" method="post" action="javascript:sendAutoformulario()">

					<input name="subject" type="hidden" value="{{ $data['title'] }}">

					@foreach($data['formulario'] as $k => $item)

						@if ($data['formulario'][$k]['type'] == "Hidden")
						
							{!! $data['formulario'][$k]['formulario']!!}
						
						@elseif ($data['formulario'][$k]['type'] == "TextArea")
							<div class="input-effect col-xs-12">
								{!! $data['formulario'][$k]['formulario']!!}
								<label><b class="red">*</b>{{ trans(\Config::get('app.theme').'-app.global.'.$k) }}</label>
							</div>
						
						@elseif ($data['formulario'][$k]['type'] == "Image")
							<div class="col-xs-12">
								{!! $data['formulario'][$k]['formulario']!!}		
							</div>
						@else
							<div class="input-effect col-xs-12 col-md-6">
								{!! $data['formulario'][$k]['formulario']!!}
								<label><b class="red">*</b>{{ trans(\Config::get('app.theme').'-app.global.'.$k) }}</label>
							</div>
						@endif

					@endforeach
					<div class="clearfix"></div>
					<br>
					<div class="row">
						
						<div class="col-xs-12">
							<div class="check_term row">
                                <div class="col-xs-2 col-md-1">
                                    <input type="checkbox" class="newsletter" name="condiciones" value="on" id="bool__1__condiciones" autocomplete="off">
                                </div>
                                <div class="col-xs-10 col-md-11">
                                    <label for="accept_new"><?= trans(\Config::get('app.theme') . '-app.emails.privacy_conditions') ?></label>
                                </div>
                            </div>
						</div>
						<div class="clearfix"></div>
						<br><br>
						<div class="col-xs-12 hidden-xs hidden-sm col-md-3"></div>
						<div class="col-xs-12 col-md-6">
							<div class="g-recaptcha"
								  data-sitekey="{{\Config::get('app.codRecaptchaEmailPublico')}}"
								  data-callback="onSubmit"
								  >
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<br><br><br>
					
					<div class="col-xs-12 text-center">
						{!! $data['submit'] !!}
					</div>

				</form>
			</div>
		</div>

		@if (isset($data['content']))
			<div class="col-md-5 col-xs-12" style="padding:20px 0 0 40px;">
				{!! $data['content'] !!}
			</div>
		@endif

	</div>
</div>   

<br><br><br>


@stop