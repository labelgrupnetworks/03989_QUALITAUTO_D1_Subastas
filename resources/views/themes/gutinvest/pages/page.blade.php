@extends('layouts.default')

@section('title')
	{{ $data['data']->name_web_page }}
@stop

@section('content')
<?php 
$bread[] = array("name" =>$data['data']->name_web_page  );
?>
    <section class="bread-new">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="titlepageBig-bread">{{ $data['data']->name_web_page }}</h1>
            </div>
        </div>
    </div>
        @include('includes.breadcrumb')
</section>
<div id="pagina-{{ $data['data']->id_web_page }}" class="contenido">
          <div class="container">
		<?php 
			
                  echo ($data['data']->content_web_page);
		?>
		
	</div>            
</div>    


@stop

