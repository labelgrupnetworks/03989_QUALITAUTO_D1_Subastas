<div class="tabs tabs-bottom tabs-primary">
    <ul class="nav nav-tabs nav-justified">
        @foreach (config('app.locales') as $languageKey => $languageName)
            <li @if ($loop->first) class="active" @endif>
                <a href="#{{ $languageKey }}_edit" data-toggle="tab" class="text-center">
                    {{ $languageName }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach (config('app.locales') as $languageKey => $languageName)
            <div id="{{ $languageKey }}_edit" class="tab-pane @if ($loop->first) active @endif">
                @php
                    $languageKey = strtoupper($languageKey);
                    $categoryLang = $category->languages->where('lang_category_blog_lang', $languageKey)->first();
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.nombre') }}
                                <input type="text" name="name_{{ $languageKey }}" class="form-control" required
                                    maxlength="50" value="{{ $categoryLang->name_category_blog_lang }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.titulo') }}
                                <input type="text" name="title_{{ $languageKey }}" class="form-control" required
                                    maxlength="50" value="{{ $categoryLang->title_category_blog_lang }}">
                            </label>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.link') }}
                                <input type="text" name="url_{{ $languageKey }}" class="form-control"
                                    maxlength="255" value="{{ $categoryLang->url_category_blog_lang }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.meta_title') }}
                                <input type="text" name="meta_title_{{ $languageKey }}" class="form-control"
                                    maxlength="67" value="{{ $categoryLang->metatit_category_blog_lang }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.meta_description') }}
								<input type="text" name="meta_desc_{{ $languageKey }}" class="form-control"
                                maxlength="155" value="{{ $categoryLang->metades_category_blog_lang }}">
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="form-control-label">
                                {{ trans('admin-app.placeholder.meta_content') }}
                                <div name="meta_cont_{{ $languageKey }}" class="summernote summernote_descrip"
                                    data-plugin-summernote
                                    data-plugin-options="{ 'height': 900, 'codemirror': { 'theme': 'ambiance' } }"
                                    rows="5">
                                    {!! $categoryLang->metacont_category_blog_lang !!}
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    $().tab;
    $('.summernote').summernote('reset');
</script>
