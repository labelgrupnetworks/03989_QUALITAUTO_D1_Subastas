@extends('admin::layouts.logged')

@section('content')
    @php

        use App\Models\V5\Web_Blog;
        $noticia = $data['noticia'] ?? [];

        $noticiaLocale = new Web_Blog();
        if (!empty($noticia['lang'])) {
            $noticiaLocale = $noticia['lang']->where('lang_web_blog_lang', mb_strtoupper(Config::get('app.locale')))->first();
        }
        /**
         * @todo
		 * # Propiedades
		 * [] Añadir categoría principal como relacionada si no se ha añadido
         *
         * # Editor de contenido
         * [?] Crear bloque para archivo
         *
         * Mejoras
         * [] Añadir puntos informativos en SEO
         * [] Duplicar bloques entre el mismo idioma y entre idiomas
         * */
    @endphp

    <input type="hidden" name="add_block"
        value="{{ route('admin.contentido.content.store', ['id' => $noticiaLocale->id_web_blog]) }}">
    <input type="hidden" name="edit_block"
        value="{{ route('admin.contentido.content.update', ['id' => $noticiaLocale->id_web_blog, 'id_content' => 0]) }}">

    <input type="hidden" name="css_styles" value="{{ trans('admin-app.config.css_framework_5') }}">

    <section role="main" class="content-body pb-5" id="blog-edit-page">

        @include('admin::includes.header_content')

        <form action="{{ route('admin.contenido.blog.update', ['id' => $noticiaLocale->id_web_blog]) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" class="id_input" name="id" value="{{ $noticiaLocale->id_web_blog ?? '0' }}">

            @include('admin::pages.contenido.blog._form_edit')
        </form>

        @include('admin::pages.contenido.blog._content')

    </section>

    <div class="modal fade" tabindex="-1" role="dialog" id="edit-block-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.title.html_content") }}</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_content_page_modal" value="">
                    <input type="hidden" name="rel_id" value="">
                    <div id="gjs" style="position: relative"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                    <button type="button" class="btn btn-primary" onclick="saveHtmlBlock()">{{ trans("admin-app.button.save_changes") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="banner-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.title.banner") }}</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100" id="content-frame" style="width: 100%; height: calc(100vh - 22rem); "></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" tabindex="-1" role="dialog" id="text-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.title.text") }}</h4>
                </div>
                <div class="modal-body">
					<input type="hidden" name="id_content_page_modal" value="">
                    <input type="hidden" name="rel_id" value="">
                    {!! FormLib::TextAreaTiny('', false, '', "", "", '600') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
					<button type="button" class="btn btn-primary" onclick="saveTextEditor()">{{ trans("admin-app.button.save_changes") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="layout-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans("admin-app.title.layout") }}</h4>
                </div>
                <div class="modal-body">
                    <p>{{ trans("admin-app.fields.select_initial_template") }}:</p>
                    <div class="grid-layouts">
                        <button class="btn btn-default js-btn-layout" type="button" data-layout="1" data-rel-id=""
                            data-type-content="">
                            <img src="/themes_admin/porto/assets/images/layouts/container_fluid.png" alt="">
                        </button>
                        <button class="btn btn-default js-btn-layout" type="button" data-layout="2" data-rel-id=""
                            data-type-content="">
                            <img src="/themes_admin/porto/assets/images/layouts/container.png" alt="">
                        </button>
                        <button class="btn btn-default js-btn-layout" type="button" data-layout="3" data-rel-id=""
                            data-type-content="">
                            <img src="/themes_admin/porto/assets/images/layouts/container_2_1.png" alt="">
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="custom-spinner"></div>
                    <div clas="custom-spinner-text">
                        <p>{{ trans("admin-app.information.processing_data") }} <br><br></p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        let editor;
        const subSections = @json($data['sub_categ']);
        const subSectionsArray = Object.values(subSections);
        const images = @json($data['images'] ?? []);
    </script>
@stop
