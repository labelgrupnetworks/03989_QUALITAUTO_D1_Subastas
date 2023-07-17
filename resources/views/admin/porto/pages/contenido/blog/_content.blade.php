@push('admin-css')
    <link href="{{ $base_url }}/vendor/grapejs/0.21.1/css/grapes.min.css" rel="stylesheet" />
@endpush

@push('admin-js')
    <script src="{{ $base_url }}/vendor/grapejs/0.21.1/grapes.min.js"></script>
    <script src="{{ $base_url }}/vendor/grapejs/0.21.1/grapesjs-preset-newsletter.min.js"></script>
@endpush

@php
    use App\Models\V5\Web_Content_Page;
@endphp

<div id="contentCms">

    <div class="row">
        <div class="col-xs-12 panel">
            <div class="panel-body">

                <div class="tabs tabs-bottom tabs-primary">

                    <ul class="nav nav-tabs nav-justified">

                        @foreach ($noticia['lang']->sortKeysDesc()->keys() as $keyLang)
                            <li class="{{ $loop->first ? 'active' : '' }}">
                                <a href="#contents_{{ $keyLang }}" data-toggle="tab" class="text-center">
                                    {{ $keyLang }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">

                        @foreach ($noticia['lang']->sortKeysDesc() as $noticia)
                            <div id="contents_{{ $noticia->lang_web_blog_lang }}"
                                class="tab-pane {{ $loop->first ? 'active' : '' }}">

                                <div class="" style="display: flex; flex-direction: column; gap: 3rem;">
                                    @foreach ($noticia->contents as $content)
                                        <div class="row content-block" data-id="{{ $content->id_content_page }}">

											<div class="col-xs-1 d-flex align-items-center p-2" style="gap: 1rem">
                                                <h2 class="d-inline-block m-0 js-order-position">
                                                    {{ $content->order_content_page }}
												</h2>

                                                <div class="btn-group-vertical" role="group"
                                                    aria-label="Cambio de orden">
                                                    <button type="button" class="btn btn-xs btn-default"
                                                        onclick="changeOrder({{ $noticia->id_web_blog_lang }}, {{ $content->id_content_page }}, 'up')">
                                                        <i class="fa fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-xs btn-default"
                                                        onclick="changeOrder({{ $noticia->id_web_blog_lang }}, {{ $content->id_content_page }}, 'down')">
                                                        <i class="fa fa-arrow-down"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-xs-11 pr-0">
                                                <div class="content-block-content">

													<div class="content-block-options">

														<div class="content-block-type">
															{{ Web_Content_Page::getTypeContentPagesName($content->type_content_page) }}
														</div>

														@if($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_HTML)
															<button type="button" class="btn btn-xs btn-info"
																data-toggle="modal" data-target="#edit-block-modal"
																data-id="{{ $content->id_content_page }}"
																data-rel-id="{{ $noticia->id_web_blog_lang }}">
																Editar
															</button>
														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_TEXT)
															<button type="button" class="btn btn-xs btn-info"
																data-toggle="modal" data-target="#text-modal"
																data-id="{{ $content->id_content_page }}"
																data-rel-id="{{ $noticia->id_web_blog_lang }}">
																Editar
															</button>
														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
															<button type="button" class="btn btn-xs btn-info"
																onclick="editBlockBanner({{ $content->rel_id_content_page }}, {{ $content->id_content_page }}, {{ $content->type_id_content_page }})">
																Editar
															</button>
														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE)
															<label type="button" class="btn btn-xs btn-info"
																for="uploadFile_{{ $content->id_content_page }}">
																Editar
															</label>
														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_VIDEO)
															<label type="button" class="btn btn-xs btn-info"
																for="uploadFile_{{ $content->id_content_page }}">
																Editar
															</label>
														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
															<button type="button" class="btn btn-xs btn-info">
																Editar
															</button>
														@endif

														{{-- @if($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
															<button type="button" class="btn btn-xs btn-warning"
																onclick="editBlockBanner({{ $content->rel_id_content_page }}, {{ $content->id_content_page }}, {{ $content->type_id_content_page }})">
																Clonar
															</button>
														@endif --}}


														<button type="button" class="btn btn-xs btn-danger"
															onclick="deleteBlock({{ $noticia->id_web_blog_lang }}, {{ $content->id_content_page }})">
															Borrar
														</button>
													</div>

													<div class="content_{{ $content->id_content_page }}">

														@if(in_array($content->type_content_page, [Web_Content_Page::TYPE_CONTENT_PAGE_HTML, Web_Content_Page::TYPE_CONTENT_PAGE_TEXT]))
															<iframe
																src="data:text/html;charset=utf-8;base64,{{ base64_encode(trans('admin-app.config.css_framework_5') . $content->content) }}"
																frameborder="0" style="width: 100%; min-height: 300px;">
															</iframe>

															<div class="html-block hidden d-none">
																{!! $content->content !!}
															</div>

														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_BANNER)
															@if ($content->type_id_content_page)
																@php
																	$banner = $data['banner'][$content->type_id_content_page];
																@endphp

																<div class="d-flex align-items-center p-1">
																	<div class="mr-5">
																		<p>Tipo:</p>
																		<img src="/themes_admin/porto/assets/img/tipo{{ $banner['id_web_newbanner_tipo'] }}.jpg"
																			alt=""
																			style="width: 100px; height: 100px; object-fit: cover;">
																	</div>

																	<div>
																		<p>Imágen principal:</p>
																		<img src="{{ $banner['image'] }}" alt=""
																			style="width: 200px; height: 100px; object-fit: cover;">
																	</div>

																</div>
															@else
																<h4>Pendiente de crear...</h4>
															@endif

														@elseif(in_array($content->type_content_page, [Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE, Web_Content_Page::TYPE_CONTENT_PAGE_VIDEO]))

															<div
																class="uploadOuter @if (!empty($content->content)) d-none @endif">
																<span class="dragBox">
																	Haga click o arrastre una imagen
																	<input type="file" onchange="dragNdrop(event)"
																		ondragenter="drag(this)" ondrop="dropOrLeave(this)"
																		ondragleave="dropOrLeave(this)"
																		data-id-content="{{ $content->id_content_page }}"
																		data-rel-id="{{ $noticia->id_web_blog_lang }}"
																		data-type-content="{{ $content->type_content_page }}"
																		id="uploadFile_{{ $content->id_content_page }}" />
																</span>
															</div>

															@if($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IMAGE)
															<div class="image-wrapper @if (empty($content->content))d-none @endif" style="height: 300px">
																<img src="{{ $content->content }}" alt="" style="width: 100%; height: 100%; object-fit: contain">
															</div>

															@else
															<div class="video-wrapper @if (empty($content->content))d-none @endif" style="height: 300px">
																<video src="{{ $content->content }}" controls style="width: 100%; height: 100%; object-fit: contain"></video>
															</div>
															@endif


														@elseif($content->type_content_page == Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
															<iframe src="{{ $content->content }}" frameborder="0" style="width: 100%; min-height: 300px;"></iframe>
														@endif

													</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="d-flex align-items-center justify-content-center mt-3 pt-2 pb-2 bg-default">
                                    <div class="btn-group dropup">
                                        <button id="addingContent" type="button" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"
                                            class="btn btn-lg btn-info br-100 dropdown-toggle">+</button>

                                        <ul class="dropdown-menu" aria-labelledby="addingContent">
                                            @foreach (Web_Content_Page::getConstantsTypesContentPages() as $contentType)
                                                <li>
                                                    @if ($contentType === Web_Content_Page::TYPE_CONTENT_PAGE_HTML)
                                                        <button type="button" class="btn btn-block btn-link"
                                                            data-rel-id="{{ $noticia->id_web_blog_lang }}"
                                                            data-type-content="{{ $contentType }}"
                                                            data-target="#layout-modal" data-toggle="modal">
                                                            {{ Web_Content_Page::getTypeContentPagesName($contentType) }}
                                                        </button>
                                                    @elseif ($contentType === Web_Content_Page::TYPE_CONTENT_PAGE_IFRAME)
                                                        <button type="button" class="btn btn-block btn-link js-add-iframe"
                                                            data-rel-id="{{ $noticia->id_web_blog_lang }}"
                                                            data-type-content="{{ $contentType }}">
                                                            {{ Web_Content_Page::getTypeContentPagesName($contentType) }}
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-block btn-link js-add-new-block"
                                                            data-rel-id="{{ $noticia->id_web_blog_lang }}"
                                                            data-type-content="{{ $contentType }}">
                                                            {{ Web_Content_Page::getTypeContentPagesName($contentType) }}
                                                        </button>
                                                    @endif
                                                </li>
                                                @if (!$loop->last)
                                                    <li role="separator" class="divider"></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
