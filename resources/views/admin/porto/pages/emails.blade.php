@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
	<header class="page-header">
		<h2></h2>

		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="javascript:;">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span> </span></li>
			</ol>

			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-lg-12">
			<section class="card panel">
				<div class="panel-body">
					<header class="card-header">
						<div class="col-md-6">
							<form action="/admin/email_clients" method="get">
								<div class="col-md-6">
									<div class="input-group" style="margin-top: 10px;">
										<span class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</span>
										<input name='date' type="text" data-plugin-datepicker class="form-control">
									</div>
								</div>
								<div class="col-md-6">
									<button type="submit"
										class="mb-xs mt-xs mr-xs btn btn-md btn-primary">{{ trans('admin-app.title.search') }}</button>
								</div>
						</div>
						</form>
				</div>
				</header>
				<div class="card-body">
					<table class="table table-responsive-md table-striped mb-0">
						<thead>
							<tr>
								<th>#</th>
								@if(!empty($data['emails']))
								@foreach(head($data['emails']) as $key_date => $email_key)
								<th>{{$key_date}}</th>
								@endforeach
								@endif
							</tr>
						</thead>
						<tbody>
							@foreach($data['emails'] as $key_cod => $email_cod)
							<?php $cont=0; ?>
							<tr>
								<td>{{$data['codigos'][$key_cod]->des_txtcod ?? 'not cod'}}</td>
								@foreach($email_cod as $emails)
								<?php $cont++; ?>
								<td>{{$emails}}</td>
								@endforeach
								<?php
                                                       for($cont;$cont<7;$cont++){
                                                            echo" <td>0</td>";
                                                        }
                                                        ?>
							</tr>
							@endforeach

						</tbody>
					</table>
				</div>
		</div>
</section>
</div>

</div>


</section>

@stop
