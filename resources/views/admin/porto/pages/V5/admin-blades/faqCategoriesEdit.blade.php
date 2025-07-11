@extends('admin::layouts.logged')
@section('content')
<?php
	$cbs=isset($_GET['cbs'])?$_GET['cbs'] : '';
	$see =  isset($_GET['see'])?$_GET['see'] : 'B' ;
?>
<section role="main" class="content-body">

	<div id="faqs">

		<section class="panel">
			<div class="panel-body">

				<h1>Faqs - Edit Category</h1>

				<div class="tabs tabs-bottom tabs-primary">
					<form name="formWEB_FAQCAT" id="formWEB_FAQCAT" method="post" action="javascript:saveFaqCat();">

						<br>
						<div class="row">
							<div class="col-xs-12 col-md-6">
								<label>Categoria:</label>
								{!! $data['formulario']['PARENT_FAQCAT'] !!}
							</div>


							{!! $data['formulario']['COD_FAQCAT'] !!}

								<div class="col-xs-12 col-md-6">
									<label>Título:</label>
									{!! $data['formulario']['NOMBRE_FAQCAT'] !!}
								</div>
							</div>

							<br><br>
							<center>{!! $data['formulario']['SUBMIT'] !!}</center>
							<br>
						</div>
					</form>

			</div>
		</section>
	</div>
</section>

@stop
