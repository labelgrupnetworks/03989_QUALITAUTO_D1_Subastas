@extends('layouts.default')

@php

$eventoGa ="";
	#si es subasta de venta directa
	if( (!empty($codSub) && $codSub =='MOTORV') || request("typeSub") == "V" ){
		$eventoGa ="ga('send','event','VISITA LISTADO','Contraoferta');";
		$seo_data->h1_seo = "<strong>Ofertas de coches en Venta Directa</strong>";
		$seo_data->meta_title = "▷ Coches de Ocasión mediante Contraoferta | Carlandia";
		$seo_data->meta_description = "Adquiere un coche de ocasión mediante oferta directa o mediante nuestro novedoso sistema de contraofertas en el que tu indicas el precio justo";

		#si además tiene categoria
		if(!empty($infoOrtsec)){
			$seoText =$infoOrtsec->des_ortsec0;
			if(!empty($infoSec)){
				$seoText .=" ".$infoSec->des_sec;
			}
			$seo_data->h1_seo = "Ofertas de Coches <strong> $seoText </strong>  en Venta Directa";
			$seo_data->meta_title = "▷ Ofertas de coches $seoText de ocasión | Carlandia";
			$seo_data->meta_description = "Hazte con un coche $seoText de ocasión al mejor precio a través de nuestra plataforma especializada en venta directa a particulares. ¡No esperes más!";

		}
		#si es subasta online
	}elseif( (!empty($codSub) && $codSub =='MOTORO') || request("typeSub") == "O" ){
		$eventoGa ="ga('send','event','VISITA LISTADO','Subasta');";
		#SEO por defecto
		$seo_data->h1_seo = "<strong> Nuestra selección de coches en Subasta Online para particulares</strong>";
		$seo_data->meta_title = "▷ Subastas de coches para particulares | Carlandia";
		$seo_data->meta_description = "En Carlandia te ofrecemos la primera plataforma nacional para realizar pujas por coches de forma online y con todas las garantías. ¡Haz tu puja ya!";
		if(!empty($infoOrtsec)){
			$seoText =$infoOrtsec->des_ortsec0;
			if(!empty($infoSec)){
				$seoText .=" ".$infoSec->des_sec;
			}
			$seo_data->h1_seo = "Coches <strong> $seoText </strong> en Subasta Online para particulares ";
			$seo_data->meta_title = "Coches $seoText en Subastas y Venta Directa | Carlandia";
			$seo_data->meta_description = "Hazte con el mejor coche $seoText a través de subastas online y venta directa en nuestra innovadora plataforma. ¡Entra ya!";

		}
	}
	else{#si no es subasta ni venta directa
		$eventoGa ="ga('send','event','VISITA LISTADO','Total');";
		$seo_data->h1_seo = "<strong>Coches en Subasta Online y Venta Directa para particulares</strong>";
		$seo_data->meta_title = "▷ Subastas de coches para particulares | Carlandia";
		$seo_data->meta_description = "En Carlandia te ofrecemos la primera plataforma nacional para realizar pujas por coches de forma online y con todas las garantías. ¡Haz tu puja ya!";

		if(!empty($infoOrtsec)){
			$seoText =$infoOrtsec->des_ortsec0;
			if(!empty($infoSec)){
				$seoText .=" ".$infoSec->des_sec;
			}
			$seo_data->h1_seo = "Coches <strong> $seoText </strong> en Subasta Online y Venta Directa para particulares";
			$seo_data->meta_title = "Coches $seoText en Subastas y Venta Directa | Carlandia";
			$seo_data->meta_description = "Hazte con el mejor coche $seoText a través de subastas online y venta directa en nuestra innovadora plataforma. ¡Entra ya!";

		}
	}

	if(!empty(request("description"))){
		$eventoGa ="ga('send','event','USO BUSCADOR LISTADO','Buscador por palabras');";
	}elseif(!empty(request("reference"))){
		$eventoGa ="ga('send','event','USO BUSCADOR LISTADO','Buscador por oferta');";
	}

@endphp


@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<script>
	$(function () {
		{!! $eventoGa !!}
	})
</script>
{{--
	Si quieren mostrar nombre de la subasta o que se vea texto Lotes - @if(request()->route()->getName() != 'home')
<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center">
			<h1 class="titlePage-custom color-letter text-center">{{$seo_data->h1_seo}}</h1>
			@include('includes.breadcrumb')
		</div>
	</div>
</div>
@endif --}}

@include('content.grid')

@stop

