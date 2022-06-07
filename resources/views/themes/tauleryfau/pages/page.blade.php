@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php 
$bread[] = array("name" =>$data['data']->name_web_page  );
?>


<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
          <div class="container-had">
		<?php 
			
                  echo ($data['data']->content_web_page);
		?>
		
	</div>            
</div>    


@stop

