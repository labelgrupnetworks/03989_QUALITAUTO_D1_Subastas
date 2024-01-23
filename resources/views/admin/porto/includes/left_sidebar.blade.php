<?php
$config_menu_admin = Config::get('app.config_menu_admin');
$traducciones = new \App\Models\Translate;
$trans = $traducciones->headersTrans();
$idiomes = \Config::get('app.locales');
?>
<aside id="sidebar-left" class="sidebar-left">

	<div class="sidebar-header">
		<div class="sidebar-title">
			Navigation
		</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html"
			data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<ul class="nav nav-main">

					<li class="nav-active">
						<a target="blanck_" href="/">
							<i class="fa fa-home" aria-hidden="true"></i>
							<span>Web</span>
						</a>
					</li>

					@if(Session::get('user.admin'))

					<li class="nav-parent @if(request('menu', '') == 'usuarios' || !empty($menu) && $menu == 'usuarios' ) nav-expanded @endif">
						<a href="#">
							<i class="fa fa-user" aria-hidden="true"></i>
							<span>Usuarios</span>
						</a>
						@if(in_array('usuarios', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/cliente">
									<span>Clientes</span>
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('clientes', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('clientes.index') }}"><span>{{ trans_choice("admin-app.title.client", 2) }}</span></a>
							</li>
						</ul>
						@endif
						<ul class="nav nav-children">
							<li>
								@if(config('app.newsletter_table', 0))
								<a href="{{ route('newsletter.index') }}">
									<span>Newsletter</span>
								</a>
								@else
								<a href="{{ route('user_newsletter.index') }}">
									<span>Newsletter</span>
								</a>
								@endif
							</li>
						</ul>
					</li>

					@if(in_array('subastas', $config_menu_admin) || in_array('newsubastas', $config_menu_admin) || in_array('concursal', $config_menu_admin) || in_array('ordenLotesDestacados', $config_menu_admin))
					<li class="nav-parent @if(request('menu', '') == 'subastas' || !empty($menu) && $menu == 'subastas' ) nav-expanded @endif">
						<a href="#">
							<i class="fa fa-gavel" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.auctions') }}</span>
						</a>
						@if(in_array('subastas',$config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/subasta">
									<span>{{ trans('admin-app.title.auctions') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('newsubastas', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('subastas.index') }}">
									<span>{{ trans('admin-app.title.auctions') }}</span>
									@if (config('app.test-admin', false))
									<sup> Vers. 2</sup>
									@endif
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('stock', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('subastas.stock.index') }}">
									<span>{{ trans('admin-app.title.stock') }}</span>

								</a>
							</li>
						</ul>
						@endif
						@if (in_array('ordenLotesDestacados', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route("subastas.lotes.order_destacadas_edit") }}">
									<span>{{ trans("admin-app.button.sort") }}
										{{ trans('admin-app.title.featured_sales') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('concursal',$config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('subastas_concursales.index') }}">
									<span>{{ trans('admin-app.title.auction_concursal') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(!in_array('noLicit', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/licit">
									<span>{{ trans('admin-app.title.licits') }}</span>
								</a>
							</li>
						</ul>
						@endif

						@if(!empty(array_intersect(['subConditions', 'dev'], $config_menu_admin)))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('subasta_conditions.index') }}">
									<span>Condiciones aceptadas</span>
								</a>
							</li>
						</ul>
						@endif

						@if(!in_array('noOrders', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('orders.index') }}">
									<span>{{ trans('admin-app.title.orders') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(!in_array('noAwards', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/award">
									<span>{{ trans('admin-app.title.awards') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(in_array('lotsNotAwards', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('not_award.index') }}">
									<span>{{ trans('admin-app.title.not_awards') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(!in_array('noCategories', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/category">

									<span>{{ trans('admin-app.title.categories') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(!in_array('noSubCategories', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/subcategory">

									<span>{{ trans('admin-app.title.subcategories') }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if(!in_array('noEscalados', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/escalado">
									<span>Escalados</span>
								</a>
							</li>
						</ul>
						@endif
						@if(in_array('depositos',$config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('deposito.index', ['menu' => 'subastas']) }}">
									<span>{{ trans('admin-app.title.deposits') }}</span>
								</a>
							</li>
						</ul>
						@endif

						@if(config('app.restrictVisibility'))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('visibilidad.index') }}">
									<span>{{ trans('admin-app.title.visibility') }}</span>
								</a>
							</li>
						</ul>
						@endif

						@if(config('app.useNft', false))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('nft.index') }}">
									<span>{{ trans("admin-app.title.nfts") }}</span>
								</a>
							</li>
						</ul>
						@endif

					</li>
					@endif

					@if(in_array('facturacion', $config_menu_admin) || in_array('proveedores', $config_menu_admin) || in_array('pedidos', $config_menu_admin))

					<li class="nav-parent @if(!empty($menu) && $menu == 'facturacion') nav-expanded @endif">
						<a href="#">
							<i class="fa fa-credit-card" aria-hidden="true"></i>
							<span>Facturación</span>
						</a>

						@if (in_array('proveedores', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('providers.index') }}">
									<i class="fa fa-truck" aria-hidden="true"></i>
									<span>{{ trans_choice('admin-app.title.provider', 2) }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('pedidos', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('pedidos.index') }}">
									<i class="fa fa-shopping-cart" aria-hidden="true"></i>
									<span>{{ trans_choice('admin-app.title.pedidos', 2) }}</span>
								</a>
							</li>
						</ul>
						@endif
						@if (in_array('facturacion', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('bills.index') }}">
									<i class="fa fa-credit-card" aria-hidden="true"></i>
									<span>{{ trans_choice('admin-app.title.bill', 2) }}</span>
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
								<span>Articulos</span>
							</a>
						</li>
					@endif

					@if(in_array('credito',$config_menu_admin))
					<li>
						<a href="{{ route('credito.index') }}">
							<i class="fa fa-credit-card" aria-hidden="true"></i>
							<span>Credito</span>
						</a>
					</li>
					@endif


					@if(in_array('sliders',$config_menu_admin))
					<li>
						<a href="/admin/sliders">
							<i class="fa fa-photo" aria-hidden="true"></i>
							<span>Sliders</span>
						</a>
					</li>
					@endif
					@if(in_array('cms',$config_menu_admin))
					<li>
						<a href="/admin/cms">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>CMS Content</span>
						</a>
					</li>
					@endif
					@if(in_array('config',$config_menu_admin))
					<li>
						<a href="/admin/configuracion">
							<i class="fa fa-gear" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.config_general') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('bloque',$config_menu_admin))
					<li>
						<a href="/admin/bloque">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.bloque') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('newbanner',$config_menu_admin))
					<li>
						<a href="/admin/newbanner">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.banners') }}</span>
						</a>

					</li>
					@endif
					@if(in_array('newbannerHome',$config_menu_admin))
					<li>
						<a href="/admin/newbanner/ubicacionhome">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.banners') }} Home</span>
						</a>

					</li>
					@endif

					@if(!empty(array_intersect(['contenido', 'edit_emails', 'blog_cms', 'dev'], $config_menu_admin)))
					<li class="nav-parent @if(request('menu', '') == 'contenido' || (!empty($menu) && $menu == 'contenido') ) nav-expanded @endif" style="">
						<a href="#">
							<i class="fa fa-user" aria-hidden="true"></i>
							<span>Contenido</span>
						</a>
						@if(in_array('contenido',$config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('event.index', ['ubicacion' => \App\Models\WebNewbannerModel::UBICACION_MUSEO, 'menu' => 'contenido'])}}">
									<span>Piezas Museo</span>
								</a>
							</li>
						</ul>

						<ul class="nav nav-children">
							<li>
								<a href="{{ route('event.index', ['ubicacion' => \App\Models\WebNewbannerModel::UBICACION_EVENTO, 'menu' => 'contenido'])}}">
									<span>Eventos</span>
								</a>
							</li>
						</ul>
						@endif

						@if (in_array('dev', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="/admin/static-pages">
									<span>Páginas Estáticas</span>
								</a>
							</li>
						</ul>
						@endif

						@if (in_array('edit_emails', $config_menu_admin))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('emails.index') }}">
									<span>Edición de Emails</span>
								</a>
							</li>
						</ul>
						@endif

						@if (!empty(array_intersect(['blog_cms', 'dev'], $config_menu_admin)))
						<ul class="nav nav-children">
							<li>
								<a href="{{ route('admin.contenido.blog.index') }}">
									<span>Blog</span>
								</a>
							</li>
						</ul>
						@endif


					</li>
					@endif

					@if(in_array('artist',$config_menu_admin))
						<li>
							<a href="{{ route('artist.index', ['menu' => 'artist']) }}">
								<i class="fa fa-align-left" aria-hidden="true"></i>
								<span>{{ trans('admin-app.title.artist') }}</span>
							</a>
						</li>
					@endif

					@if(in_array('favorites',$config_menu_admin))
					<li>
						<a href="/admin/favoritos">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.favorites') }}</span>
						</a>
					</li>

					@endif
					@if(in_array('articulos',$config_menu_admin))
					<li>
						<a href="/admin/resources?see=A">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.articulos') }}</span>
						</a>
					</li>
					<li>
						<a href="/admin/banner?see=N">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.noticias') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('calendar',$config_menu_admin))
					<li>
						<a href="/admin/banner?see=C">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.calendar') }}</span>
						</a>
					</li>
					<li>
						<a href="/admin/resources?see=C">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.calendar_recurso') }}</span>
						</a>
					</li>

					@endif
					@if(in_array('newemail',$config_menu_admin))
					<li>
						<a href="/admin/email">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.emails') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('aux_index',$config_menu_admin))
					<li>
						<a href="/admin/auc-index">
							<i class="fa fa-align-left" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.tematicas') }}</span>
						</a>
					</li>
					@endif
					@endif
					@if(in_array('seo_familias_sessiones',$config_menu_admin))
					<li>
						<a href="/admin/seo-familias-sessiones">
							<i class="fa fa-align-left" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.seo_familias_sessiones') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('seo_categories',$config_menu_admin))
					<li>
						<a href="/admin/seo-categories">
							<i class="fa fa-align-left" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.seo_categories') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('content_page',$config_menu_admin))
					<li>
						<a href="/admin/content">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.content_page') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('faq',$config_menu_admin))
					<li>
						<a href="/admin/faqs/{{\Config::get('app.locale')}}">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.faq') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('traducciones',$config_menu_admin))
					<li class="nav-parent @if(!empty($menu) && $menu == 'translates') nav-expanded @endif" style="">
						<a href="#">
							<i class="fa fa-columns" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.traducciones') }}</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/traducciones/search">
									<span>{{ trans('admin-app.title.search_traductions') }}</span>
								</a>
							</li>
						</ul>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/traducciones">
									<span>{{ trans('admin-app.title.traducciones') }}</span>
								</a>
							</li>
						</ul>
					</li>
					@endif
					@if(in_array('new_calendar',$config_menu_admin))
					<li>
						<a href="/admin/calendar">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.calendar') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('emails_clients',$config_menu_admin))
					<li>
						{{--
						<a href="/admin/email_clients">
							<i class="fa fa-folder" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.emails') }}</span>
						</a>
						--}}
						<a href="{{ route('adminemails.showlog') }}">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.emails_log') }}</span>
						</a>
					</li>
					@endif
					@if(in_array('blog',$config_menu_admin))
					<li class="nav-parent @if(!empty($menu) && $menu == 'blog') nav-expanded @endif" style="">
						<a href="#">
							<i class="fa fa-columns" aria-hidden="true"></i>
							<span>{{ trans('admin-app.title.blog') }}</span>
						</a>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/blog-admin">
									<span>{{ trans('admin-app.title.article') }}</span>
								</a>
							</li>
						</ul>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/category-blog">
									<span>{{ trans('admin-app.title.category_blog') }}</span>
								</a>
							</li>
						</ul>
					</li>
					@endif

					@if(in_array('bi',$config_menu_admin))
						<li>
							<a  href="/admin/bi/report/categoryAwardsSales?years[]={{date("Y")}}">
								<i class="fa fa-pie-chart" aria-hidden="true"></i>
								<span>{{ trans('admin-app.title.bi_reports') }}</span>
							</a>

						</li>
					@endif

					@if(strtoupper(session('user.usrw')) == 'SUBASTAS@LABELGRUP.COM')
					<li class="nav-parent @if(!empty($menu) && $menu == 'configuracion_admin') nav-expanded @endif">
						<a href="#">
							<i class="fa fa-gear" aria-hidden="true"></i>
							<span>Configuración interna</span>
						</a>

						<ul class="nav nav-children">
							<li>
								<a href="{{ route('admin.jobs.index') }}">
									<span>Jobs</span>
								</a>
							</li>
						</ul>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/cache">
									<span>Cache</span>
								</a>
							</li>
						</ul>
						<ul class="nav nav-children">
							<li>
								<a href="/admin/log-viewer" target="_blank">
									<span>Logs</span>
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
