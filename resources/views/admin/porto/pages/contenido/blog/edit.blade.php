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
         * [x] Botón de activar / desactivar
         * [x] Botón de vista previa
         * [x] Botón de guardar
         * [x] Subir o actualizar imagen
         * [x] Subsecciones relacionadas, cargar según sección
         * [x] Comprobar sin id
         * [x] Los botónes de activar / desactivar y vista previa no deben aparecer en la creación
         * [x] La selección de imágen al crear, debe ser enviada por el formulario, no por ajax
         * [x] El formulario o creo una blade distinta para crear y editar o extraigo el form a los archivos edit y create
         *
         * # Editor de contenido
         * [x] Crar bloque para banner
         * [x] Crear bloque para html
		 * 		[x] Añadir assets al html
         * [x] Crear bloque para imagen
		 * 		[x] Añadir imagen
		 * 		[x] Editar imagen
		 * 		[x] Eliminar imagen
         * [x] Crear bloque para video
		 * 		[x] Añadir video
		 * 		[x] Editar video
		 * 		[x] Eliminar video
         * [x] Crear bloque para iframe
         * [?] Crear bloque para archivo
         *
         * # General
         * [x] Modificar rutas y metodos del controlador
         *
         * # Metodos blog
         * [x] index - Ver todos
         * [x] create
         * [x] store
         * [x] edit
         * [x] update
         * [~] show - Ver uno - solo idioma principal
         *
         * [x] añadir contenido
         * [x] editar contenido
         * [x] eliminar contenido
         * [x] modificar orden de contenido
         *
         * Mejoras
         * [x] Añadir loaders
         * [] Añadir puntos informativos
         * [] Duplicar bloques entre el mismo idioma y entre idiomas
         * [x] Añadir botón de eliminar bloque
         * [x] Seleccionar layout al crear html
         * */
    @endphp

    <input type="hidden" name="add_block"
        value="{{ route('admin.contentido.content.store', ['id' => $noticiaLocale->id_web_blog]) }}">
    <input type="hidden" name="edit_block"
        value="{{ route('admin.contentido.content.update', ['id' => $noticiaLocale->id_web_blog, 'id_content' => '']) }}">

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
                    <h4 class="modal-title">Contenido HTML</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_content_page_modal" value="">
                    <input type="hidden" name="rel_id" value="">
                    <div id="gjs" style="position: relative"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="saveHtmlBlock()">Guardar Cambios</button>
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
                    <h4 class="modal-title">Banner</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100" id="content-frame" style="width: 100%; height: calc(100vh - 22rem); "></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                    <h4 class="modal-title">Texto</h4>
                </div>
                <div class="modal-body">
					<input type="hidden" name="id_content_page_modal" value="">
                    <input type="hidden" name="rel_id" value="">
                    {!! FormLib::TextAreaTiny('', false, '', "", "", '600') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="saveTextEditor()">Guardar Cambios</button>
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
                    <h4 class="modal-title">Layout</h4>
                </div>
                <div class="modal-body">
                    <p>Seleccionar la plantilla inicial:</p>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
                        <p>Procesando datos... <br><br></p>
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
