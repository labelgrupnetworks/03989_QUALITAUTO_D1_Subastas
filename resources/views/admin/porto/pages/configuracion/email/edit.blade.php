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


	<div id="newbanner">

		<a href="/admin/email" class="right btn btn-primary">Volver</a>

		<h1>Emails - Editar</h1>
		<br>
		<div class="row">
			<div class="col-12 col-md-4">
				<b>Key identificativa</b>
        		<br>
        		{!! $formulario['cod_email']!!}
			</div>
	        <div class="col-12 col-md-4">
	        	<b>Tipo de email</b>
        		<br>
        		{!! $formulario['type_email'] !!}
	        </div>
        	<div class="col-12 col-md-2 text-center">
        		<b>Activo</b>
        		<br>
        		{!! $formulario['enabled_email'] !!}
        	</div>
        </div>

        <div class="row">
        	<div class="col-12">
        		<b>Descripción del email</b>
        		<br>
        		{!! $formulario['des_email'] !!}
        	</div>
        </div>
        
        @csrf
        

        <br><hr><br>

        <div class="row">
        <div class="col-12 col-md-6">

        	<h3>Editor</h3>
        	<br>
        	@foreach(\Config::get("app.locales") as $lang => $textLang)
        		<a href="javascript:seleccionaIdioma('{{$lang}}')" class="btn btn-primary">{{ $textLang }}</a>
        		&nbsp;
        	@endforeach
        	<br><br>

	        @foreach(\Config::get("app.locales") as $lang => $textLang)
	        <div class="tab" id="tab{{$lang}}" style="display:none">
	        	<div class="row">
		        	<div class="col-12">
		        		<b>Asunto {{$textLang}}</b>
	        			<br>
	        			{!! $formulario['subject_email'][$lang] !!}
		        	</div>
		        </div>
		        <br>
		        <div class="row">
	        		<div class="col-12">
	        			<b>Cuerpo {{$textLang}}</b>
        				<br>
		        		{!! $formulario['body_email'][$lang] !!}
		        	</div>
		        </div>
	        </div>
	        @endforeach
	    </div>
        <div class="col-12 col-md-6" id="preview">


        </div>
		
			
	</div>	

	<script type="text/javascript">$("#tabes").show();</script>

@stop
