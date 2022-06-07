<th class="{{$field}}" style="cursor: pointer" data-order="{{$field}}">
	{{ trans("admin-app.fields.$field") }}
	@if(request()->order == $field)
	<span style="margin-left: 5px; float: right;">
		@if(request()->order_dir == 'asc')
		<i class="fa fa-arrow-up" style="color:green" aria-hidden="true"></i>
		@else
		<i class="fa fa-arrow-down" style="color:red" aria-hidden="true"></i>
		@endif
	</span>
	@endif
</th>
