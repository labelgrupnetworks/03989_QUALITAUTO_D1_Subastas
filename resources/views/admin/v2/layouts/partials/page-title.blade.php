<!-- ========== Page Title Start ========== -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="mb-0 fw-semibold">{{ $title }}</h4>
            @if ($subTitle)
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $subTitle }}</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            @endif
        </div>
    </div>
</div>
<!-- ========== Page Title End ========== -->
