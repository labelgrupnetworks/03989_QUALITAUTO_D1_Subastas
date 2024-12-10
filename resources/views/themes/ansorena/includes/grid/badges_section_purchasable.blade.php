<section class="section-badges">

    @if (!empty(request('description')))
        <span data-del_filter="#description" class="del_filter_js badge badge-custom-primary">
            @include('components.boostrap_icon', ['icon' => 'x-circle'])
            <span>{{ request('description') }}</span>
        </span>
    @endif

    @if (!empty(request('reference')))
        <span data-del_filter="#reference" class="del_filter_js badge badge-custom-primary">
            @include('components.boostrap_icon', ['icon' => 'x-circle'])<span>{{ request('reference') }}</span>
        </span>
    @endif
</section>
