<div class="" id="admin_settings_box">
    <div class="gestor_buttons">
        @foreach ($data as $sectionName => $section)
            <p class="opacity-75 small">{{ $sectionName }}</p>
            @foreach ($section as $setting)
                <a class="btn btn-outline-lb-secondary btn-sm w-100 mb-1" href="{{ $setting['url'] }}" title="{{ $setting['name'] }}"
                    target="_blank">
                    <span>
                        {{ $setting['name'] }}
                    </span>
                </a>
            @endforeach
        @endforeach
    </div>
    <div class="desplegable">
        <div data-id="right">
            <svg class="bi bi-arrow-right-short m-auto d-block" width="20" height="20" fill="currentColor"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8" />
            </svg>
        </div>
        <div class="hidden" data-id="left">
            <svg class="bi bi-arrow-left-short m-auto d-block" width="20" height="20" fill="currentColor"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5" />
            </svg>
        </div>
    </div>
</div>
