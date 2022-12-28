
<div class="row well">
	<div class="col-xs-12">

		<h1>Licitadores</h1>
		<a href="{{$formularioCreate}}" class="btn btn-primary right">Nuevo Licitador</a>
		<form id="idAuctionForExport" name="idAuctionForExport" action="/admin/licit/export-licits" method="POST">
			<input id="idAuctionForExcel" type="hidden" name="idAuctionExcel" value="">
			<a id="submitLicitExport" onclick="javascript:submit_form(document.getElementById('idAuctionForExport'),0);" class="btn btn-warning right mr-2 d-none">Exportar licitadores</a>
		</form>

		<p><i class="fa fa-2x fa-info-circle" style="position:relative;top:6px;"></i>&nbsp;<span class="badge">
				La selección de la subasta es obligatoria</span></p>
		<br>

		<form name="whereLicits" id="whereLicits" action="/admin/licit/show" method="GET" class="col-11">

			@csrf

			<div class="row">
				@foreach($formulario as $index => $item)

				@if ($index != 'SUBMIT' && $index != 'HIDDEN')
				<div class="col-xs-12 col-md-6" style="padding-bottom:15px;">
					<div class="row">
						<div class="col-xs-3 pt-2 text-right">
							<label>{{ ucfirst($index)}}: </label>
						</div>
						<div class="col-xs-9">
							{!! $item !!}
						</div>
					</div>
				</div>
				@elseif ($index != "SUBMIT")
				{!! $item !!}
				@endif

				@endforeach
				<div class="col-xs-12 col-md-6 text-right">{!! $formulario['SUBMIT'] !!}</div>
			</div>

		</form>

	</div>
</div>

<div class="row well">
	<div class="col-xs-12">
		<table id="tableLicits" class="table table-striped table-bordered hover" style="width:100%">
			<thead>
				<th>Cod Cli</th>
				<th>Id Origen Cli</th>
				<th>Cod Licitador</th>
				<th>Rsoc Cli</th>
				{{--<th>Acc.</th>--}}
			</thead>
		</table>
	</div>
</div>

<script>
	$('#select__1__SUB_LICIT').change(function() {
		$('#idAuctionForExcel').val($(this).val());
		if ($('#idAuctionForExcel').val() != '') {
			$('#submitLicitExport').removeClass('d-none');
		} else {
			$('#submitLicitExport').addClass('d-none');
		}
	});
</script>

