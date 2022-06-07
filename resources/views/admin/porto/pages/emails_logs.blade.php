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

					<div class="card-body">

						<table class="table table-responsive-md table-striped mb-0" style="width: 100%">
							@foreach ($web_emails_logs as $web_emails_log)

							@if($loop->first)

							<thead>
								<tr>
									@foreach (array_keys($web_emails_log->getOriginal()) as $fields)
									<th>{{ trans("admin-app.fields.$fields") }}</th>
									@endforeach
								</tr>
							</thead>

							<tbody>
								@endif

								<tr>
									<td>{{ $web_emails_log->rn }}</td>
									<td>{{ $web_emails_log->id_email_logs }}</td>
									<td>{{ $web_emails_log->codtxt_email_logs }}</td>
									<td>{{ $web_emails_log->email_email_logs }}</td>
									<td>{{ $web_emails_log->typeEmailFormat }}</td>
									<td>{{ $web_emails_log->sub_email_logs }}</td>
									<td>{{ $web_emails_log->ref_email_logs }}</td>
									<td>{{ $web_emails_log->numhces_email_logs }}</td>
									<td>{{ $web_emails_log->date_email_logs }}</td>
								</tr>

								@if($loop->first)
							</tbody>
							@endif



							@endforeach
						</table>
					</div>
					<div class="col-xs-12 text-center">
						{{ $web_emails_logs->links() }}
					</div>
				</div>
			</section>
		</div>

	</div>


</section>

@stop
