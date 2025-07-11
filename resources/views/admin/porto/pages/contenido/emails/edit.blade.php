@extends('admin::layouts.logged')
@section('content')

@push('admin-css')
@endpush

@push('admin-js')
@endpush

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h2>Email {{ $email->cod_email }}</h2>
			<h4>{{ $email->des_email }}</h4>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('emails.index') }}" class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>

	<div class="block-edit">
		<div class="row well">
			<form action="{{ route('emails.update', ['email' => $email->cod_email]) }}" method="POST">
				@method('PUT')
				@csrf
				<div class="col-xs-12 mb-2">
					<label for="">Asunto</label>
					{!! $subject !!}
				</div>
				<div class="col-xs-12 mb-2">
					<label for="">Etiquetas diponibles</label>
					<div class="tags-container">
						<span>{{ $tags }}</span>
					</div>
				</div>

				<div class="col-xs-12">
					<label for="">Contenido</label>
					{!! $textarea !!}
					{{-- <textarea class="form">{{$email->body_email}}</textarea> --}}
				</div>

				<div class="col-xs-12">
					<button type="submit" class="btn btn-primary">Guardar</button>
				</div>

			</form>
		</div>

		<div class="row well">
			<div class="col-xs-12">
				<h3>Vista Previa:</h3>
			</div>
			{{-- {!! $email->body_email !!} --}}
			<div class="col-xs-12 mb-2">
				<iframe width="100%" srcdoc="{{$html}}" onload="resizeIframe(this)" frameborder="0" scrolling="no" style="box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px, rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px, rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;"></iframe>
			</div>
		</div>
	</div>

	<script>
	function resizeIframe(obj) {
    	obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
  	}
	</script>

	@stop
