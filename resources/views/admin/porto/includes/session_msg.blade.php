@if(session('errors'))
@php
	$formatErrors = is_array($errors) ? $errors : $errors->all();
@endphp

@foreach ($formatErrors as $error)
<div class="alert alert-danger" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
			aria-hidden="true">&times;</span></button>
	<strong>{{ $error }}</strong>
</div>
@endforeach
@endif

@foreach (session('success') ?? [] as $key => $success)
<div class="alert alert-success" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
			aria-hidden="true">&times;</span></button>
	<strong>
		{{-- show key only if not number --}}
		{{ intval($key) === $key ? '' : $key . ': ' }}
		{{ $success}}
	</strong>
</div>
@endforeach

@foreach (session('warning') ?? [] as $key => $warning)
<div class="alert alert-warning" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
			aria-hidden="true">&times;</span></button>
	<strong>
		{{ intval($key) === $key ? '' : $key . ': ' }}
		{!! $warning !!}
	</strong>
</div>
@endforeach
