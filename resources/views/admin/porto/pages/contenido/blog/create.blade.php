@extends('admin::layouts.logged')

@section('content')
    @php

        use App\Models\V5\Web_Blog;
        $noticia = $data['noticia'] ?? [];

		//dd($noticia);

        $noticiaLocale = new Web_Blog();
        if (!empty($noticia['lang'])) {
            $noticiaLocale = $noticia['lang']->where('lang_web_blog_lang', mb_strtoupper(Config::get('app.locale')))->first();
        }
    @endphp

    <section role="main" class="content-body pb-5" id="blog-edit-page">

        @include('admin::includes.header_content')

		<form action="{{ route('admin.contenido.blog.store') }}" method="POST" enctype="multipart/form-data">
			@csrf

        @include('admin::pages.contenido.blog._form_edit')
		</form>

    </section>

    <script>
		const subSections = @json($data['sub_categ']);
		const subSectionsArray = Object.values(subSections);
    </script>
@stop
