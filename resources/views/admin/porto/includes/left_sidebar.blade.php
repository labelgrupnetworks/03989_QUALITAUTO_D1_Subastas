<?php
$config_menu_admin = Config::get('app.config_menu_admin');

$isLabelAdmin = strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM';
$isB2bWihtLabel = in_array('b2b', $config_menu_admin) && $isLabelAdmin;
?>
<aside class="sidebar-left" id="sidebar-left">

    <div class="sidebar-header">
        <div class="sidebar-title">
            {{ trans('admin-app.title.navigation') }}
        </div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html"
            data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav class="nav-main" id="menu" role="navigation">
                <ul class="nav nav-main">

                    <li class="nav-active">
                        <a href="/" target="blanck_">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>{{ trans('admin-app.nav_menu.web') }}</span>
                        </a>
                    </li>

                    @if ($isB2bWihtLabel)
                        <li>
                            <a href="{{ route('admin.b2b.companies') }}">
                                <i class="fa fa-building" aria-hidden="true"></i>
                                <span>Empresas</span>
                            </a>
                        </li>
						<li>
							<a href="{{ route('clientes.index') }}">
								<i class="fa fa-user" aria-hidden="true"></i>
								<span>{{ trans('admin-app.nav_menu.clients') }}</span>
							</a>
						</li>

                    @endif

                    @if (!in_array('b2b', $config_menu_admin))
                        <li class="nav-parent @if (request('menu', '') == 'usuarios' || (!empty($menu) && $menu == 'usuarios')) nav-expanded @endif">
                            <a href="#">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.users') }}</span>
                            </a>
                            @if (in_array('usuarios', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/cliente">
                                            <span>{{ trans('admin-app.nav_menu.clients') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('clientes', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a
                                            href="{{ route('clientes.index') }}"><span>{{ trans('admin-app.nav_menu.clients') }}</span></a>
                                    </li>
                                </ul>
                            @endif
                            <ul class="nav nav-children">
                                <li>
                                    @if (config('app.newsletter_table', 0))
                                        <a href="{{ route('newsletter.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.newsletter') }}</span>
                                        </a>
                                    @else
                                        <a href="{{ route('user_newsletter.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.newsletter') }}</span>
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array('subastas', $config_menu_admin) ||
                            in_array('newsubastas', $config_menu_admin) ||
                            in_array('concursal', $config_menu_admin) ||
                            in_array('ordenLotesDestacados', $config_menu_admin) ||
                            $isB2bWihtLabel)
                        <li class="nav-parent @if (request('menu', '') == 'subastas' || (!empty($menu) && $menu == 'subastas')) nav-expanded @endif">
                            <a href="#">
                                <i class="fa fa-gavel" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.auctions') }}</span>
                            </a>
                            @if (in_array('newsubastas', $config_menu_admin) || $isB2bWihtLabel)
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subastas.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.auctions') }}</span>
                                            @if (config('app.test-admin', false))
                                                <sup> {{ trans('admin-app.information.version2') }}</sup>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('stock', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subastas.stock.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.stock') }}</span>

                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('ordenLotesDestacados', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subastas.lotes.order_destacadas_edit') }}">
                                            <span>{{ trans('admin-app.button.sort') }}
                                                {{ trans('admin-app.nav_menu.featured_sales') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('concursal', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subastas_concursales.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.auction_concursal') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('reports', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subasta.reports.index') }}">
                                            <span>Informes</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (!in_array('noLicit', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/licit">
                                            <span>{{ trans('admin-app.nav_menu.licits') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (!empty(array_intersect(['subConditions', 'dev'], $config_menu_admin)))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('subasta_conditions.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.accepted_conditions') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (!in_array('noOrders', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('orders.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.orders') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

							<ul class="nav nav-children">
								<li>
									<a href="{{ route('admin.bids.index') }}">
										<span>Pujas</span>
									</a>
								</li>
							</ul>

                            @if (!in_array('noAwards', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/award">
                                            <span>{{ trans('admin-app.nav_menu.awards') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('lotsNotAwards', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('not_award.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.not_awards') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (!in_array('noCategories', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/category">

                                            <span>{{ trans('admin-app.nav_menu.categories') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (!in_array('noSubCategories', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/subcategory">

                                            <span>{{ trans('admin-app.nav_menu.subcategories') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (!in_array('noEscalados', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/escalado">
                                            <span>{{ trans('admin-app.nav_menu.scaleds') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('depositos', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('deposito.index', ['menu' => 'subastas']) }}">
                                            <span>{{ trans('admin-app.nav_menu.deposits') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (config('app.restrictVisibility'))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('visibilidad.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.visibility') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (config('app.useNft', false))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('nft.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.nfts') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                        </li>
                    @endif

                    @if (in_array('facturacion', $config_menu_admin) ||
                            in_array('proveedores', $config_menu_admin) ||
                            in_array('pedidos', $config_menu_admin))

                        <li class="nav-parent @if (!empty($menu) && $menu == 'facturacion') nav-expanded @endif">
                            <a href="#">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.facturation') }}</span>
                            </a>

                            @if (in_array('proveedores', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('providers.index') }}">
                                            <i class="fa fa-truck" aria-hidden="true"></i>
                                            <span>{{ trans('admin-app.nav_menu.providers') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('pedidos', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('pedidos.index') }}">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                            <span>{{ trans('admin-app.nav_menu.pedidos') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif
                            @if (in_array('facturacion', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('bills.index') }}">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                            <span>{{ trans('admin-app.nav_menu.bills') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif


                        </li>
                    @endif

                    @if (in_array('articles', $config_menu_admin))
                        <li>
                            <a href="{{ route('articles.index') }}">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.articles') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('credito', $config_menu_admin))
                        <li>
                            <a href="{{ route('credito.index') }}">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.credit') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('config', $config_menu_admin))
                        <li>
                            <a href="/admin/configuracion">
                                <i class="fa fa-gear" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.general_config') }}</span>
                            </a>
                        </li>
                    @endif
                    {{-- @if (in_array('bloque', $config_menu_admin))
                        <li>
                            <a href="/admin/bloque">
                                <i class="fa fa-columns" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.blocks') }}</span>
                            </a>
                        </li>
                    @endif --}}
                    @if (in_array('newbanner', $config_menu_admin) || $isB2bWihtLabel)
                        <li>
                            <a href="/admin/newbanner">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.banners') }}</span>
                            </a>

                        </li>
                    @endif
                    @if (in_array('newbannerHome', $config_menu_admin))
                        <li>
                            <a href="/admin/newbanner/ubicacionhome">
                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.home_banners') }}</span>
                            </a>

                        </li>
                    @endif

                    @if (!empty(array_intersect(['contenido', 'edit_emails', 'blog_cms', 'dev'], $config_menu_admin)))
                        <li class="nav-parent @if (request('menu', '') == 'contenido' || (!empty($menu) && $menu == 'contenido')) nav-expanded @endif" style="">
                            <a href="#">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.content') }}</span>
                            </a>
                            @if (in_array('contenido', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a
                                            href="{{ route('event.index', ['ubicacion' => \App\Models\WebNewbannerModel::UBICACION_MUSEO, 'menu' => 'contenido']) }}">
                                            <span>{{ trans('admin-app.nav_menu.museum_pieces') }}</span>
                                        </a>
                                    </li>
                                </ul>

                                <ul class="nav nav-children">
                                    <li>
                                        <a
                                            href="{{ route('event.index', ['ubicacion' => \App\Models\WebNewbannerModel::UBICACION_EVENTO, 'menu' => 'contenido']) }}">
                                            <span>{{ trans('admin-app.nav_menu.events') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (in_array('dev', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="/admin/static-pages">
                                            <span>{{ trans('admin-app.nav_menu.static_pages') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (in_array('edit_emails', $config_menu_admin))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('emails.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.email_editor') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (!empty(array_intersect(['blog_cms', 'dev'], $config_menu_admin)))
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('admin.contenido.blog.index') }}">
                                            <span>{{ trans('admin-app.nav_menu.blog') }}</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif

                            @if (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')
                                <ul class="nav nav-children">
                                    <li>
                                        <a href="{{ route('admin.contenido.uploads.index') }}">
                                            <span>Archivos</span>
                                        </a>
                                    </li>
                                </ul>
                            @endif


                        </li>
                    @endif

                    @if (in_array('artist', $config_menu_admin))
                        <li>
                            <a href="{{ route('artist.index', ['menu' => 'artist']) }}">
                                <i class="fa fa-paint-brush" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.artist') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('favorites', $config_menu_admin))
                        <li>
                            <a href="/admin/favoritos">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.favorites') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('newemail', $config_menu_admin))
                        <li>
                            <a href="/admin/email">
                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.emails') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('content_page', $config_menu_admin) || $isB2bWihtLabel)
                        <li>
                            <a href="/admin/content">
                                <i class="fa fa-desktop" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.web_content') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('faq', $config_menu_admin))
                        <li>
                            <a href="/admin/faqs/{{ \Config::get('app.locale') }}">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.faq') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('traducciones', $config_menu_admin))
                        <li class="nav-parent @if (!empty($menu) && $menu == 'translates') nav-expanded @endif" style="">
                            <a href="#">
                                <i class="fa fa-globe" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.translates') }}</span>
                            </a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/traducciones/search">
                                        <span>{{ trans('admin-app.nav_menu.translation_searcher') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/traducciones">
                                        <span>{{ trans('admin-app.nav_menu.translates') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (in_array('new_calendar', $config_menu_admin))
                        <li>
                            <a href="/admin/calendar">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.calendar') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('emails_clients', $config_menu_admin))
                        <li>
                            <a href="{{ route('adminemails.showlog') }}">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.emails_log') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('blog', $config_menu_admin))
                        <li class="nav-parent @if (!empty($menu) && $menu == 'blog') nav-expanded @endif" style="">
                            <a href="#">
                                <i class="fa fa-columns" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.blog') }}</span>
                            </a>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/blog-admin">
                                        <span>{{ trans('admin-app.nav_menu.entries') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/category-blog">
                                        <span>{{ trans('admin-app.nav_menu.categories') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

					{{-- Solamente lo utiliza Gutinvest. Actualizar a los nuevos banners --}}
					@if(in_array('banners',$config_menu_admin))
					<li>
						<a href="/admin/resources?see=all">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.resources') }}</span>
						</a>
					</li>
					<li>
						<a href="/admin/banner?see=B">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.banners') }}</span>
						</a>

					</li>
					@endif

                    @if (in_array('bi', $config_menu_admin))
                        <li>
                            <a href="/admin/bi/report/categoryAwardsSales?years[]={{ date('Y') }}">
                                <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.bi_reports') }}</span>
                            </a>

                        </li>
                    @endif

                    @if (in_array('b2b', $config_menu_admin) && !$isLabelAdmin)
                        <li>
                            <a href="{{ route('admin.b2b.users') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>Usuarios</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.b2b.lots') }}">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>Lotes</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.b2b.bids') }}">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>Pujas</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.b2b.awards') }}">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>Adjudicaciones</span>
                            </a>
                        </li>
                    @endif

                    @if (strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')
                        <li class="nav-parent @if (!empty($menu) && $menu == 'configuracion_admin') nav-expanded @endif">
                            <a href="#">
                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                <span>{{ trans('admin-app.nav_menu.internal_config') }}</span>
                            </a>

                            <ul class="nav nav-children">
                                <li>
                                    <a href="{{ route('admin.thumbs.index') }}">
                                        <span>Generar miniaturas</span>
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav nav-children">
                                <li>
                                    <a href="{{ route('admin.test-auctions.index') }}">
                                        <span>Subastas de pruebas</span>
                                    </a>
                                </li>
                            </ul>

                            <ul class="nav nav-children">
                                <li>
                                    <a href="{{ route('admin.jobs.index') }}">
                                        <span>{{ trans('admin-app.nav_menu.jobs') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/cache">
                                        <span>{{ trans('admin-app.nav_menu.cache') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/log-viewer" target="_blank">
                                        <span>{{ trans('admin-app.nav_menu.logs') }}</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="/admin/mesures" target="_blank">
                                        <span>Estadísticas</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-children">
                                <li>
                                    <a href="{{ route('admin.disk-status.index') }}">
                                        <span>Comprobar disco</span>
                                    </a>
                                </li>
                            </ul>

                        </li>
                    @endif

                </ul>



            </nav>


            <hr class="separator" />

        </div>

    </div>

</aside>
