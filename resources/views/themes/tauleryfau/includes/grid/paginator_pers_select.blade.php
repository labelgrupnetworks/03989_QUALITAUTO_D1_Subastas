
@if ($paginator->hasPages())
    <div class="pagination-select-wrapper">
        <form action="{{$paginator->url(0)}}" name="pagination">
            <select class="pagination-select form-control" name="pagination-select">

				@for ($page = 1; $page <= $paginator->lastPage(); $page++)
					<option value="{{ $page }}" @if ($page == $paginator->currentPage()) selected @endif>{{ trans("$theme-app.lot_list.page") }} {{ $page }}</option>
				@endfor

            </select>
        </form>
    </div>
@endif
