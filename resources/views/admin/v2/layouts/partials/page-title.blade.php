<!-- ========== Page Title Start ========== -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0 fw-semibold">{{ data_get($layout, 'title', '') }}</h4>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin">Backoffice</a></li>
                @if ($layout['section'])
                    <li class="breadcrumb-item text-capitalize">{{ $layout['section'] }}</li>
                @endif
                <li class="breadcrumb-item active">{{ data_get($layout, 'title', '') }}</li>
            </ol>
        </div>
    </div>
</div>
<!-- ========== Page Title End ========== -->

