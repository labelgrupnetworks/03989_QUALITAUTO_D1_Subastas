<section class="card card-featured-left card-featured-primary mb-4">
	<div class="card-body">
		<div class="widget-summary widget-summary-sm">
			<div class="widget-summary-col widget-summary-col-icon">
				<div class="summary-icon {{ $iconBg ?? 'bg-info' }}">
					<i class="{{ $iconClass ?? 'fa fa-plus' }}"></i>
				</div>
			</div>
			<div class="widget-summary-col">
				<div class="summary">
					<h4 class="title">{{ $title ?? 'Title' }}</h4>
					<div class="info">
						<strong class="amount">{{ $value ?? 'Value' }}</strong>
						<span class="text-primary">{{ $value ?? 'Value' }}</span>
					</div>
				</div>
				<div class="summary-footer">
					<a class="text-muted text-uppercase">({{ $value ?? 'Value' }})</a>
				</div>
			</div>
		</div>
	</div>
</section>
