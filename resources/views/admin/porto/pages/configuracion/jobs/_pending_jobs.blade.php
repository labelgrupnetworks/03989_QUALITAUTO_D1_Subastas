<div class="col-xs-12 table-responsive">
	<table id="clientes" class="table table-striped table-condensed table-responsive"
		style="width:100%">
		<thead>
			<tr>
				<th>{{ trans("admin-app.fields.id") }}</th>
				<th>{{ trans("admin-app.fields.environment") }}</th>
				<th>{{ trans("admin-app.fields.class_name") }}</th>
				<th>{{ trans("admin-app.fields.tries") }}</th>
				<th>{{ trans("admin-app.fields.disponibility_date") }}</th>
				<th>{{ trans("admin-app.fields.created_date") }}</th>
				<th>
					<span>{{ trans('admin-app.fields.actions') }}</span>
				</th>
			</tr>
		</thead>

		<tbody>

			@forelse ($pendigJobs as $job)
				<tr id="{{ $job->id }}">
					<td>{{ $job->id }}</td>
					<td>{{ $job->queue }}</td>
					<td>{{ $job->job_name }}</td>
					<td>{{ $job->attempts }}</td>
					<td>{{ $job->available_at_format }}</td>
					<td>{{ $job->created_at_format }}</td>

					<td>
						<button class="btn btn-xs btn-success" data-toggle="modal" data-target="#jobModal" data-route="{{ route('admin.jobs.pending', ['id' => $job->id]) }}">
							<i class="fa fa-eye"></i>
						</button>
					</td>
				</tr>

			@empty

				<tr>
					<td colspan="6">
						<h3 class="text-center">{{ trans('admin-app.title.without_results') }}
						</h3>
					</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</div>
