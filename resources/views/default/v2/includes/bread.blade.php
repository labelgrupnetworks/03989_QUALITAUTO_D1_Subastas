@if (!empty($bread))
<nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
	aria-label="breadcrumb">
	<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
		<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a itemtype="https://schema.org/Thing" itemprop="item"
				title="{{ trans(\Config::get('app.theme').'-app.subastas.breadcrumb') }}"
				href="https://{{ \Request::getHttpHost() }}">

				<span itemprop="name">{{ trans(\Config::get('app.theme').'-app.subastas.breadcrumb') }}</span>
			</a>
			<meta itemprop="position" content="1"/>
		</li>
		@foreach ($bread as $crumb)

			<li class="breadcrumb-item @if($loop->last) active @endif>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
				@if($loop->last) aria-current="page" @endif>
					<a class="color-letter bread-link"
						itemtype="https://schema.org/Thing"
						itemprop="item"
						title="{{ $crumb['name'] }}"
						href="{{ $crumb["url"] ?? "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" }}">

						<span itemprop="name">{!! $crumb["name"] !!}</span>
					</a>
					<meta itemprop="position" content="{{ $loop->index + 2 }}" />
			</li>

		@endforeach
	</ol>
</nav>
@endif
