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
@if(session('success'))
<div class="alert alert-success" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
			aria-hidden="true">&times;</span></button>
	<strong>{{ session('success')[0] }}</strong>
</div>
@endif
@if(session('warning'))
@foreach (session('warning') as $key => $warning)
<div class="alert alert-warning" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
			aria-hidden="true">&times;</span></button>
	<strong>{{ $key . ': ' .  $warning}}</strong>
</div>
@endforeach
@endif
