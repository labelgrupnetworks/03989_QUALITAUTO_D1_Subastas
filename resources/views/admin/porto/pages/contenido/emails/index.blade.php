@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		@csrf

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1 class="m-0">Emails</h1>
		</div>
	</div>

	<div class="row well">

		<div class="col-xs-12">
			@csrf
			<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
				<thead>

					<tr>
						<th>{{ trans("admin-app.fields.cod_email") }}</th>
						<th>{{ trans("admin-app.fields.des_email") }}</th>
						<th>{{ trans("admin-app.fields.subject_email") }}</th>
						<th>{{ trans("admin-app.fields.type_email") }}</th>
						<th>{{ trans("admin-app.fields.actions") }}</th>
					</tr>
				</thead>

				<tbody>

					@forelse ($emails as $email)

					<tr id="fila_{{$email->cod_email}}">
						<td>{{$email->cod_email}}</td>
						<td>{{$email->des_email}}</td>
						<td>{{$email->subject_email}}</td>


						@switch($email->type_email)
							@case('A')
							<td>Administrador</td>
								@break
							@case('L')
							<td>Licitante</td>
								@break
							@case('P')
							<td>Propietario</td>
								@break
							@default
							<td>{{$email->type_email}}</td>
						@endswitch


						<td class="d-flex w-100 gap-5">
							<a href="{{route('emails.edit', mb_strtolower($email->cod_email))}}" class="btn btn-primary btn-xs mr-1">
								<i class="fa fa-pencil"></i>
							</a>

							<button onclick="sendEmail('{{ mb_strtolower($email->cod_email) }}')" class="btn btn-success btn-xs mr-1">
								<i class="fa fa-envelope"></i>
							</button>
						</td>
					</tr>

					@empty

					<tr>
						<td colspan="6"><h3 class="text-center">{{ trans("admin-app.title.without_results") }}</h3></td>
					</tr>

					@endforelse
				</tbody>
			</table>

		</div>
	</div>

	<script>
		function sendEmail(codEmail) {
			const userEmail = prompt("Introduce la dirección de correo electrónico a la que enviar el email:");
			if (!userEmail) {
				alert("El email es obligatorio.");
				return;
			}
			if (confirm("¿Estás seguro de que quieres enviar el email a " + userEmail + "?")) {

				$.ajax({
					url: "{{ route('admin.emails.send') }}".replace(':email', userEmail),
					type: "POST",
					data: {
						cod_email: codEmail,
						user_email: userEmail,
						_token: $('input[name="_token"]').val()
					},
					success: function(response) {
						alert("¡Email enviado con éxito!");
					},
					error: function(xhr, status, error) {
						alert("Error al enviar el email: " + error);
					}
				});
			}
		}
	</script>

	@stop
