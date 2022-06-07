<div class="box small-box {{ $extraClass ?? ''}}" @if(!empty($id)) id="{{ $id }}" @endif>
	<span class="box-icon {{ $iconBg ?? 'bg-info' }}">
		@if(!empty($iconClass))
		<i class="{{ $iconClass ?? 'fa fa-plus' }}" aria-hidden="true"></i>
		@elseif(!empty($iconImg))
		<img src="{{ $iconImg }}" style="max-width: 60px">
		@endif
	</span>

	<div class="box-content">
	  <p class="box-title box-text">{{ $title ?? 'Title' }} <span class="box-title-value" >{{ $titleValue ?? '' }}</span></p>
	  <p class="box-sub-text">{{ $subTitle ?? 'SubTitle' }} <span class="box-subtitle-value" >{{ $subValue ?? '' }}</span></p>
	  <p class="box-number">{{ $value ?? 'Value' }} <span class="box-number-value" >{{ $numberValue ?? '' }}</span></p>
	</div>
</div>
