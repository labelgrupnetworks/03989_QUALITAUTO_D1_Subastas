@if (!empty($bread))
<nav aria-label="breadcrumb">
	<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
		<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<a itemtype="https://schema.org/Thing" itemprop="item"
				title="{{ trans($theme.'-app.subastas.breadcrumb') }}"
				href="https://{{ \Request::getHttpHost() }}">

				<span itemprop="name">{{ trans($theme.'-app.subastas.breadcrumb') }}</span>
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
