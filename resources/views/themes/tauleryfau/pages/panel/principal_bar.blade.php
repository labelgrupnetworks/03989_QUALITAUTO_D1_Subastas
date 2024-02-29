<section style="background-color: #f1ece6;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
				<div class="principal-bar no-principal">
					<div class="principal-bar-title user-panel-principal-bar d-flex justify-content-space-between w-100">
						<h3>{{ trans("$theme-app.emails.hello") }} {{ \Session::get('user.name') }}</h3>
						<h3><a href="{{ \Routing::slug('logout') }}"><i class="fas fa-times"></i> {{ trans($theme.'-app.user_panel.exit') }}</a></h3>
					</div>
				</div>
            </div>
        </div>
    </div>
</section>
