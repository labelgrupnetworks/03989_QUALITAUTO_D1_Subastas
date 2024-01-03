@extends('admin::layouts.logged')
@section('content')
    <section role="main" class="content-body">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">Cache</h1>
            </div>
        </div>

        <div class="row well">

			<div class="col-xs-12 table-responsive">
				<table id="clientes" class="table table-striped table-condensed table-responsive" style="width:100%">
					<thead>
						<tr>
							<th>Acción</th>
							<th>Descripción</th>
							<th>
								<span>Ejecutar</span>
							</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($actions as $action)
							<tr id="{{ $action->action }}">
								<td>{{ $action->title }}</td>
								<td>{{ $action->description }}</td>

								<td>
									<button class="btn btn-xs btn-danger" title="Ejecutar" data-action="{{ $action->action }}" onclick="sendAction(this.dataset)">
										<i class="fa fa-paper-plane-o"></i>
									</button>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

        </div>

    </section>

    <script>
		function sendAction({ action }) {
			if (!confirm(`¿Está seguro de ejecutar la acción ${action}?`)) {
				return;
			}

			fetch(`{{ route('admin.cache.action') }}`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify({ action })
			})
				.then(response => response.json())
				.then(({ status, message }) => {
					if (status === 'success') {
						alert(message);
						location.reload();
					} else {
						alert(message);
					}
				})
				.catch(error => {
					console.error(error);
					alert('Ocurrió un error al ejecutar la acción');
				});

			}
    </script>
@endsection
