@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop




@section('content')
<script>
    <?php // login es la págian antigua y si cierran sesion estando en una página que requiere estar logeado redirige a esta página  ?>
    window.location.replace("/{{ \App::getLocale() }}/register");

</script>