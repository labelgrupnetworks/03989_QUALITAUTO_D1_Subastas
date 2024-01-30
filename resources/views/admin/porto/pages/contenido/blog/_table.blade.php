<table id="" class="table table-striped table-condensed table-responsive" style="width:100%">
    <thead>
        <tr>
            <th>{{ trans("admin-app.fields.id") }}</th>
            {{-- <th>Imagen</th> --}}
            <th>{{ trans("admin-app.fields.title") }}</th>
            <th>{{ trans("admin-app.fields.publication_date") }}</th>
            <th>{{ trans("admin-app.fields.visible") }}</th>
            <th>{{ trans("admin-app.fields.actions") }}</th>
        </tr>
    </thead>

    <tbody>

        @forelse ($blogs as $blog)
			@php
			$lang = strtoupper(config('app.locale'));
			$localeBlog = $blog->languages->where('lang_web_blog_lang', $lang)->first();
			@endphp
            <tr id="fila{{ $blog->id_web_blog }}">
                <td>{{ $blog->id_web_blog }}</td>
                {{-- <td>
                    @if (Tools::fileNameIsImage($blog->img_web_blog))
                        <img src="{{ $blog->img_web_blog }}" alt="" width="50" height="50"
                            style="object-fit: contain" loading="lazy">
                    @else
                        <video src="{{ $blog->img_web_blog }}" alt="" width="50" height="50"></video>
                    @endif
                </td> --}}
                <td>{{ $blog->title_web_blog }}</td>
                <td>{{ $blog->publish_date }}</td>
                <td>
                    @if ($blog->is_visible)
                        <i class="fa fa-check bg-success" style="padding: 10px; border-radius: 100%"></i>
                    @elseif(!empty($blog->is_visible_message->error))
                        <i class="fa fa-times bg-danger" style="padding: 10px; border-radius: 100%"></i>
                    @elseif(!empty($blog->is_visible_message->warnings))
                        <i class="fa fa-exclamation-triangle bg-warning" style="padding: 10px; border-radius: 100%"
                            data-html="true" data-toggle="tooltip" data-placement="right"
                            title="{{ implode('<br>', $blog->is_visible_message->warnings) }}">
                        </i>
                    @endif
                </td>

                <td>
                    <div class="td-actions">
                        <button class="btn @if ($localeBlog->enabled_web_blog_lang == 1) btn-success @else btn-danger @endif"
                            data-is-enabled="{{ $localeBlog->enabled_web_blog_lang == 1 ? 'true' : 'false' }}"
                            data-id="{{ $blog->id_web_blog }}" onclick="handleClickChangeEnabledStatus(this)"><i
                                class="fa fa-power-off"></i></button>
                        <a href="{{ route('admin.contenido.blog.edit', ['id' => $blog->id_web_blog]) }}"
                            class="btn btn-primary btn-sm btn-block mt-0">{{ trans('admin-app.button.edit') }}</a>
                    </div>

                </td>
            </tr>

        @empty

            <tr>
                <td colspan="6">
                    <h3 class="text-center">{{ trans('admin-app.title.without_results') }}</h3>
                </td>
            </tr>
        @endforelse

    </tbody>

</table>
