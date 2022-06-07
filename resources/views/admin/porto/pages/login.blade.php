@extends('admin::layouts.login')
@section('content')

	<div class="inner-wrapper inner-login" id="login">
		<section class="body-sign">
			<div class="center-sign">
				<a href="/admin" class="logo pull-left">
					<img src="/themes_admin/porto/assets/img/logo.png" height="54" alt="LabelGrup" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> {{ trans('admin-app.login.login_txt') }}</h2>
					</div>
					<div class="panel-body">
						<form method="post" class="frmLogin" data-toggle="validator">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="form-group mb-lg">
								<label>{{ trans('admin-app.login.email') }}</label>
								<div class="input-group input-group-icon">
									<input class="form-control input-lg" type="email" name="email" placeholder="{{ trans('admin-app.login.email') }}" data-error="{{ trans('admin-app.login.write_valid_email') }}" required/>
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">{{ trans('admin-app.login.password') }}</label>
									{{-- <a href="pages-recover-password.html" class="pull-right">Lost Password?</a> --}}
								</div>
								<div class="input-group input-group-icon">
									<input name="password" data-minlength="3" type="password" class="form-control input-lg" placeholder="{{ trans('admin-app.login.password') }}" data-error="{{ trans('admin-app.login.write_pass') }}" required/>
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
								<div class="help-block with-errors"></div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									{{-- <div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">{{ trans('admin-app.login.remember_me') }}</label>
									</div> --}}
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" class="btnLogin btn btn-primary hidden-xs disabled pull-right">{{ trans('admin-app.login.sign_in') }}</button>
									<button type="submit" class="btnLogin btn btn-primary btn-block btn-lg visible-xs mt-lg disabled pull-right">{{ trans('admin-app.login.sign_in') }}</button>
								</div>
							</div>

						</form>

						@if (!empty($errors) && $errors->any())
						    <div class="alert alert-danger">
					            @foreach ($errors->all() as $error)
					            	<p>{{ $error }}</p>
					            @endforeach
						    </div>
						@endif

					</div>
				</div>

				{{-- <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2016. All Rights Reserved.</p> --}}
			</div>
		</section>
	</div>
@stop