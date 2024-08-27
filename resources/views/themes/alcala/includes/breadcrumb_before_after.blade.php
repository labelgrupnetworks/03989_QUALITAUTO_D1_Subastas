<div class="col-xs-12 no-padding">
    <div class="col-xs-12 col-sm-7 no-padding bread-after">
        <div class="bread">
            @include('includes.bread')
        </div>
    </div>
    <div class="col-xs-12 col-sm-5 no-padding follow">
        <div class="next d-flex align-item-center justify-content-space-between text-right">
            @if (!empty($data['previous']))
                <a class="color-letter nextLeft" href="{{ $data['previous'] }}"
                    title="{{ trans($theme . '-app.subastas.last') }}">
					<x-icon.boostrap icon=chevron-left size=11px color=#000></x-icon.boostrap>
					{{ trans($theme . '-app.subastas.last') }}
				</a>
            @endif
            @if (!empty($data['next']))
                <a class="color-letter nextRight" href="{{ $data['next'] }}"
                    title="{{ trans($theme . '-app.subastas.next') }}">
					{{ trans($theme . '-app.subastas.next') }}
					<x-icon.boostrap icon=chevron-right size=11px color=#000></x-icon.boostrap>
				</a>
            @endif
        </div>
    </div>

</div>
