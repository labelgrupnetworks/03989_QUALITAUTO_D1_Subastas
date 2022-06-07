<header class="header">
	<div class="logo-container">
		<a href="javascript:;" class="logo">
                    <img height="35" src="/themes_admin/porto/assets/img/logo.png" class="img-responsive">
		</a>
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="header-right">

		<ul class="notifications">
			<li>

			</li>
			<li>
			</li>
		</ul>

		<span class="separator"></span>

		<div id="userbox" class="userbox">
			<a href="javascript:;" data-toggle="dropdown">
				<!--<figure class="profile-picture">
				<img src="{{ $images_url }}/!happy-face.png" alt="Joseph Doe" class="img-circle" data-lock-picture="{{ $images_url }}/!happy-face.png" />
				</figure>-->
				<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
					<span class="name">{{ Session::get('user.name') }}</span>
					<!--<span class="role">administrator</span>-->
				</div>

				<i class="fa custom-caret"></i>
			</a>

			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>
					<!--
					<li>
						<a role="menuitem" tabindex="-1" href="javascript:;"><i class="fa fa-user"></i> My Profile</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="javascript:;" data-lock-screen="true"><i class="fa fa-lock"></i> Lock Screen</a>
					</li>
					-->
					<li>
						<a role="menuitem" tabindex="-1" href="/admin/logout"><i class="fa fa-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>