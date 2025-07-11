@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div id="cms">

            <section class="panel">

                <div class="panel-body">
                        <div id="datatable-default_wrapper" class="dataTables_wrapper no-footer">
                                <div class="">
                                        <table class=" bloque-conf table table-bordered table-striped mb-none dataTable no-footer" id="datatable-default" role="grid" aria-describedby="datatable-default_info">
                                            <thead>
                                                <tr>
                                                    <th>CÓDIGO EMAIL</th>
                                                    <th>DESCRIPCIÓN EMAIL</th>
                                                    <th>PLANTILLA EMAIL</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                    @foreach($data as $email)
                                                    <tr role="row" class="odd">
                                                            <td style="width: 20%; ">
                                                               {{$email->cod_email}}

                                                            </td>
                                                            <td style="width: 70%; ">
                                                               {{$email->des_email}}

                                                            </td>
                                                            <td class="text-center"><a class="mb-xs mt-xs mr-xs btn btn-primary " href="email/ver/<?= $email->cod_email ?>" target="blank">{{ trans('admin-app.title.email_ver') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                </div>
            </section>
	</div>
</section>

@stop
