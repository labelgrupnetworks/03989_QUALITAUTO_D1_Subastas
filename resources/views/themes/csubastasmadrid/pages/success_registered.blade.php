@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12">
                             <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.login_register.success_register')}}</h1>
                             <p><?= trans(\Config::get('app.theme').'-app.login_register.success_register_msg')?></p>

		</div>
	</div>
</div>
    @if(!empty(\Config::get('app.google_addwords')))
        <!-- Event snippet for Registro conversion page -->
        <script> gtag('event', 'conversion', {'send_to': '{{ \Config::get('app.google_addwords') }}/LRm6CJCXm58BEPOXhuEC'}); </script>
    @endif

@stop
