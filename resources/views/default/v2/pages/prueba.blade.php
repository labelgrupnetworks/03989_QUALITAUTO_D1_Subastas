@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')

<!--
<table border="1" style="margin-left: 50px;">
	{{--@foreach($subastas as $nomsub => $lots)
	<tr><td colspan="3">Nombre subasta: {{$nomsub}}</td></tr>
		@foreach($lots as $key=> $lot)

			<tr><td>{{$key}}</td>
				<td> Ref lote: {{$lot->ref_asigl0}}</td>
				<td> Nombre lote: {{$lot->descweb_hces1}}</td>
			</tr>
		@endforeach
	@endforeach --}}
</table> -->

<?php
$resultado="";
foreach ($array_es as $keycat=>$cat) {
	//echo "cat $keycat <br>";
	foreach($cat as $keysubcat=>$subcat){
	//	echo "		subcat: $keysubcat    $subcat <br>";
		if (!empty($array_en[$keycat])) {
				if (empty($array_en[$keycat][$keysubcat])) {
					echo "no existe subcategoria  $keycat -----> $keysubcat<br>";

				}
		}else {
			echo "no existe categoria  $keycat--> $keysubcat<br>";
		}
	}
}


print_r($resultado);

?>


@stop
