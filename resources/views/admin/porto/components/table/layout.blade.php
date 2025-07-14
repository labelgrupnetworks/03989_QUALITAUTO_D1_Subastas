<div class="row well">
    <x-admin::table.toolbar>
        {{ $toolbar ?? '' }}
    </x-admin::table.toolbar>

	<div class="col-xs-12 table-responsive">
    	{{ $table ?? '' }}
	</div>
</div>
