<div class="main-nav">
    <div class="logo-box">
        <a class="logo-dark" href="/">
            <img class="img-fluid w-75 logo-lg object-fit-contain" src="/themes/{{ $theme }}/assets/img/logo.png"
                alt="{{ trans('web.menu.logo_alt') }}">
            <img class="img-fluid w-50 logo-sm" src="/themes/{{ $theme }}/assets/img/logo-sm.png"
                alt="{{ trans('web.menu.logo_alt') }}">
        </a>

        <a class="logo-light" href="/">
            <img class="img-fluid w-75 logo-lg object-fit-contain" src="/themes/{{ $theme }}/assets/img/logo.png"
                alt="{{ trans('web.menu.logo_alt') }}">
            <img class="img-fluid w-50 logo-sm" src="/themes/{{ $theme }}/assets/img/logo-sm.png"
                alt="{{ trans('web.menu.logo_alt') }}">
        </a>
    </div>

    <button class="button-sm-hover" type="button" aria-label="{{ trans('web.menu.show_full_sidebar') }}">
        <i class="ri-menu-2-line fs-24 button-sm-hover-icon"></i>
    </button>

    <div class="scrollbar" data-simplebar>


        <ul class="navbar-nav" id="navbar-nav">
            {{-- button to section return --}}
            <li class="nav-item return-section" style="display: none;">
                <button class="nav-link" onclick="resetMenu()">
                    <span class="nav-icon">
                        {{-- <i class="ri-arrow-left-line"></i> --}}
                        <x-icon.boostrap icon="arrow-left" />
                    </span>
                    <span class="nav-text">Volver a secciones</span>
                </button>
            </li>
			<li class="menu-title section">
                	Menu
            </li>

            @foreach ($sidebarMenu as $section)
                <li class="nav-item section">
                    <button class="nav-link" onclick="toggleSubsection('{{ $section['id'] }}')">
                        <span class="nav-icon">
                            <x-icon.boostrap icon="{{ $section['icon'] }}" />
                        </span>
                        <span class="nav-text">{{ trans('admin-app.nav_menu.' . $section['label']) }}</span>
                    </button>
                </li>

				<li class="menu-title subsection" data-section="{{ $section['id'] }}" style="display: none;">
                	{{ trans('admin-app.nav_menu.' . $section['label']) }}
            	</li>

				@foreach ($section['sub_sections'] as $subSection)
					<li class="nav-item subsection" data-section="{{ $section['id'] }}" style="display: none;">
						<a class="nav-link" href="{{ $subSection['route'] }}">
							<span class="nav-icon">
								<x-icon.boostrap icon="{{ $subSection['icon'] }}" />
							</span>
							<span class="nav-text">{{ trans('admin-app.nav_menu.' . $subSection['label']) }}</span>
						</a>
					</li>
				@endforeach

            @endforeach

        </ul>
    </div>
</div>


<script>

	const menuActive = @json($layout['menu'] ?? $menu ?? '');
	document.addEventListener('DOMContentLoaded', function () {
		if (menuActive) {
			toggleSubsection(menuActive);
		}
	});

    function toggleSubsection(section) {

        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            if (item.classList.contains('subsection')) {
                item.style.display = item.dataset.section === section ? 'block' : 'none';
            }
        });

		//menu-title
		const menuTitle = document.querySelector('.menu-title[data-section="' + section + '"]');
		if (menuTitle) {
			menuTitle.style.display = 'block';
		}

        const sections = document.querySelectorAll('.section');
        sections.forEach(sec => {
            sec.style.display = 'none';
        });


        const returnSection = document.querySelector('.return-section');
        returnSection.style.display = 'block';
    }

    function resetMenu() {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            if (item.classList.contains('subsection')) {
                item.style.display = 'none';
            }
        });

		const menuTitles = document.querySelectorAll('.menu-title');
		menuTitles.forEach(title => {
			title.style.display = 'none';
		});

        const sections = document.querySelectorAll('.section');
        sections.forEach(sec => {
            sec.style.display = 'block';
        });

        const returnSection = document.querySelector('.return-section');
        returnSection.style.display = 'none';
    }
</script>
