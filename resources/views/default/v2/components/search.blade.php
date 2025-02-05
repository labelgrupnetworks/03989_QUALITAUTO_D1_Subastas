<div class="search-component-wrapper {{ $classes }}">
    <form role="search" action="{{ $searchAction }}">
        <div class="input-group">
            <input class="form-control form-control-sm" name="description" type="search"
                aria-label="{{ trans("$theme-app.head.search_label") }}" aria-describedby="button-addon2"
                placeholder="{{ trans("$theme-app.head.search_label") }}">
            <button class="btn btn-sm btn-lb-primary d-flex align-items-center" type="submit">
                <svg class="bi" width="16" height="16" fill="currentColor">
                    <use xlink:href="/bootstrap-icons.svg#search"></use>
                </svg>
            </button>
        </div>
    </form>
</div>
