<div class="col-xs-12 table-responsive">
    <table id="clientes" class="table table-striped table-condensed table-responsive" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre de clase</th>
                <th>Fecha fallo</th>
                <th>
                    <span>{{ trans('admin-app.fields.actions') }}</span>
                </th>
            </tr>
        </thead>

        <tbody>

            @forelse ($failedJobs as $job)
                <tr id="{{ $job->id }}">
                    <td>{{ $job->id }}</td>
                    <td>{{ $job->job_name }}</td>
                    <td>{{ $job->failed_at_format }}</td>

                    <td>
                        <button class="btn btn-xs btn-success" title="Ver más" data-toggle="modal" data-target="#jobModal"
                            data-route="{{ route('admin.jobs.failed', ['id' => $job->id]) }}">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-xs btn-danger" title="Reenviar" onclick="reenviar('{{$job->route_to_reesend}}')">
                            <i class="fa fa-paper-plane-o"></i>
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
