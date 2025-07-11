@extends('admin::layouts.logged')
@section('content')
    <section id="blog-index-page" role="main" class="content-body">
                @csrf

        <div class="row well header-well d-flex align-items-center">
            <div class="col-xs-9">
                <h1>{{ trans("admin-app.title.blog_cms") }}</h1>
            </div>
            <div class="col-xs-3">
                <a href="{{ route('admin.contenido.blog.create') }}"
                    class="btn btn-primary right">{{ trans('admin-app.button.new') }} {{ trans("admin-app.title.blog") }}</a>
            </div>
        </div>

        <div class="row well tabs-well">

            <div class="col-xs-3 categories-block">
                <div class="categories-list">
                    <h4>{{ trans("admin-app.title.categories") }}</h4>
                    <ul id="categoires-navs" class="nav nav-pills nav-stacked">
                        @forelse (collect($categories)->sortBy('order_category_blog') as $category)
                            <li data-id="{{ $category->id_category_blog }}" role="presentation"
                                class="categories-items btn-group-xs btn @if ($loop->first) active @endif">
                                <button class="btn btn-link js-soratble-button"><i class="fa fa-reorder"></i></button>
                                <a href="#tab_{{ $category->id_category_blog }}"
                                    aria-controls="{{ $category->title_category_blog }}" role="tab" data-toggle="tab">
                                    {{ $category->title_category_blog }}
                                </a>

                                <button class="btn btn-link" onclick="hadleChangeEnabledCategory(event)"
                                    data-is-enabled="{{ $category->enable_category_blog == 1 ? 'true' : 'false' }}">
                                    <i
                                        class="fa @if ($category->enable_category_blog) fa-eye @else fa-eye-slash @endif fa-2x"></i>
                                </button>
                                <button class="btn btn-link js-edit-category"><i class="fa fa-edit fa-2x"></i></button>
                                <button class="btn btn-link" onclick="handleDeleteCategory(event)"><i
                                        class="fa fa-trash fa-2x"></i></button>
                            </li>
                        @empty
                            <li class="list-group-item">{{ trans("admin-app.information.no_categories") }}</li>
                        @endforelse

                        @if ($blogsWithoutCategory->count() > 0)
                            <li>
                                <a href="#tab_without_category" aria-controls="Sin categoria" role="tab"
                                    data-toggle="tab">
                                    {{ trans("admin-app.information.without_categories") }} <span class="badge">{{ $blogsWithoutCategory->count() }}</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a class="js-add-new-category" href="" data-target="#category-modal" data-toggle="modal">
                                <i class="fa fa-plus"></i> {{ trans("admin-app.button.new") }} {{ trans("admin-app.title.category") }}
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="col-xs-9">

                <div class="tab-content">
                    @foreach ($categories as $category)
                        <div role="tabpanel" class="tab-pane @if ($loop->first) active @endif"
                            id="tab_{{ $category->id_category_blog }}">

                            @include('admin::pages.contenido.blog._table', [
                                'blogs' => $blogs->where('primary_category_web_blog', $category->id_category_blog)
                            ])

                        </div>
                    @endforeach

                    <div role="tabpanel" class="tab-pane" id="tab_without_category">
                        @include('admin::pages.contenido.blog._table', [
                            'blogs' => $blogsWithoutCategory,
                        ])
                    </div>

                    <div role="tabpanel" class="tab-pane" id="tab_empty">
                        @include('admin::pages.contenido.blog._table', ['blogs' => collect([])])
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" tabindex="-1" role="dialog" id="category-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.category") }}</h4>
                </div>
                <div class="modal-body">
                    <form id="form-category" name="form-category"
                        action="{{ route('admin.contenido.blog-category.store') }}" method="POST">
                        <div class="tabs tabs-bottom tabs-primary">
                            <ul class="nav nav-tabs nav-justified">
                                @foreach (config('app.locales') as $languageKey => $languageName)
                                    <li @if ($loop->first) class="active" @endif>
                                        <a href="#{{ $languageKey }}" data-toggle="tab" class="text-center">
                                            {{ $languageName }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach (config('app.locales') as $languageKey => $languageName)
                                    <div id="{{ $languageKey }}"
                                        class="tab-pane @if ($loop->first) active @endif">
                                        @php
                                            $languageKey = strtoupper($languageKey);
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.nombre') }}

                                                        <input type="text" name="name_{{ $languageKey }}"
                                                            id="name_{{ $languageKey }}" class="form-control" required
                                                            maxlength="50">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.titulo') }}
                                                        <input type="text" name="title_{{ $languageKey }}"
                                                            class="form-control" required maxlength="50">
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.link') }}
                                                        <input type="text" name="url_{{ $languageKey }}"
                                                            class="form-control" maxlength="255">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.meta_title') }}
                                                        <input type="text" name="meta_title_{{ $languageKey }}"
                                                            class="form-control" maxlength="67">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.meta_description') }}
                                                        <input type="text" name="meta_desc_{{ $languageKey }}"
                                                            class="form-control" maxlength="155">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-group">
                                                    <label class="form-control-label">
                                                        {{ trans('admin-app.placeholder.meta_content') }}
                                                        <div name="meta_cont_{{ $languageKey }}"
                                                            id="meta_cont_{{ $languageKey }}"
                                                            class="summernote summernote_descrip" data-plugin-summernote
                                                            data-plugin-options="{ 'height': 900, 'codemirror': { 'theme': 'ambiance' } }"
                                                            rows="5">
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                    <button form="form-category" type="submit" class="btn btn-primary">{{ trans("admin-app.button.save") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="category-edit-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.button.edit") }} {{ trans("admin-app.title.category") }} <span id="category-title"></span></h4>
                </div>
                <div class="modal-body">
                    <form id="form-category-update" name="form-category-update" method="POST"
                        action="{{ route('admin.contenido.blog-category.update', ['id' => '0']) }}">

                        @csrf
                        <div class="form-content"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                    <button form="form-category-update" type="submit" class="btn btn-primary">{{ trans("admin-app.button.save") }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
