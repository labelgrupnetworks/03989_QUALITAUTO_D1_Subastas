@extends('layouts.default')

@section('title')
{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php
$bread[] = array("name" =>$data['data']->name_web_page  );
?>
@include('includes.breadcrumb')

<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
	<div class="container">
		@if($data['data']->key_web_page == 'departamentos' || $data['data']->key_web_page == 'departments')
		<div class="banner-home-row-departamentos">
			{!! \BannerLib::bannersPorKey('home_departamentos', 'home-banner-departamentos','{dots:false, arrows:false,
			autoplay: true,
			autoplaySpeed: 4000, slidesToScroll:1}') !!}
		</div>
		@else
		{!! $data['data']->content_web_page !!}
		@endif
	</div>
</div>


@stop
