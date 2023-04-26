<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es_es" lang="es_es">
<head>
<base href="/" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>DUMMY</title>
<meta name="description" content="DUMMY LAYOUT" />
<meta name="title" content="DUMMY" />
<meta name="robots" content="all" />
<meta name="distribution" content="world" />
<meta name="DC.title" lang="es" content="DUMMY" /> 
<meta name="DC.creator" content="LabelGrup" /> 
<meta name="DC.subject" lang="es" content="DUMMY"/> 
<meta name="DC.description" lang="es" content="DUMMY" /> 
<meta name="DC.type" scheme="DCTERMS.DCMIType" content="Text" /> 
<meta name="DC.format" content="text/html" /> 
<meta name="DC.identifier" scheme="DCTERMS.URI" content="/" /> 
<meta name="DC.language" scheme="DCTERMS.URI" content="es" /> 
<meta name="copyright" content="Copyright (c) {{ date('Y') }} LabelGroup">
<meta http-equiv="Content-Language" content="es" />
<meta http-equiv="cache-control" content="public" />

<link href="/css/bootstrap.css" type="text/css" rel="stylesheet" media="all">

</head>

<body style="font-family:Century Gothic;border:0;margin:0;padding:0;font-size:24px;">
	<div id="header" style="text-align:center;padding:20px;color:#FFF;background:linear-gradient(#333,#000)">
		
		DUMMY PAGE for Test
		
	</div>


	<div class="container" style="width:500px;margin:auto;font-size:18px;">

		<br>

		<h1>Prueba de conexión</h1>

		<p><i>Mostramos lista de tabla de subastas:</i></p>

		@foreach ($subastas as $k => $item)
		
			<p>&middot; {{ $item->des_sub }}</p>

		@endforeach

		<br><br><br>
		<div class="well">Tiempo de carga: {{$tiempo}}</div>

		<br><br><br>

	</div>


	<div style="text-align:center;padding:20px;color:#FFF;background:linear-gradient(#A00,#800)">
		
		© {{ date('Y') }} LabelGrup Subastas.
		
	</div>



</div>

</body>
</html>
