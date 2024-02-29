@extends('admin::layouts.logged')
@section('content')
    <section role="main" class="content-body">
        @include('admin::includes.header_content')
        @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-12">
                <h1 class="m-0">{{ trans("admin-app.title.queues") }}</h1>
            </div>
        </div>

        <div class="row well">

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">
                <div class="panel panel-default">
                    <div class="panel-heading d-flex align-items-center justify-content-space-between" role="tab"
                        id="headingOne" style="background-color: #fff;">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="true"
                                aria-controls="collapseOne">
                                {{ trans("admin-app.title.queues_pending_to_send") }}
                            </a>
                        </h4>
                        <span class="badge badge-danger">{{ $pendigJobs->count() }}</span>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            @include('admin::pages.configuracion.jobs._pending_jobs')
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading d-flex align-items-center justify-content-space-between" role="tab"
                        id="headingTwo" style="background-color: #fff;">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTwo"
                                aria-expanded="false" aria-controls="collapseTwo">
                                {{ trans("admin-app.title.failed_queues") }}
                            </a>
                        </h4>
                        <span class="badge badge-danger">{{ $failedJobs->count() }}</span>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">
                            @include('admin::pages.configuracion.jobs._failed_jobs')
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- Modal -->
    <div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans("admin-app.title.infoextra") }}</h4>
                </div>
                <div class="modal-body" style="overflow-wrap: break-word;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#jobModal').on('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const route = button.dataset.route;

            fetch(route)
                .then(response => response.json())
                .then(json => {
                    const commandText = json.data.payload.data.command;
                    $('.modal-body').html(commandText);
                })
                .catch(error => {
                    console.error(error);
                })
        })

        $('#jobModal').on('hidden.bs.modal', function(e) {
            $('.modal-body').html('');
        })

        function reenviar(route) {
            fetch(route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        _token: document.querySelector('input[name="_token"]').value,
                    })
                })
                .then(response => response.json())
                .then(({
                    message
                }) => {
                    alert(message);
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                })
        }
    </script>
@endsection
