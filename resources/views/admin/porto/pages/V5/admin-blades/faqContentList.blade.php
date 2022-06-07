<div class="sortable" data-child="s1" data-isCategory=true>
    @if (isset($data['catsU']) && !empty($data['catsU']))
        @foreach ($data['catsU'] as $kf => $vf)

            <div class="card s1" id="{{ $vf['info']->cod_faqcat }}">
                <!-- FAQ Cat -->
                <div class="card-header" style="border:#CCC 1px solid; background:#DDD;padding:10px;">
                    <div class="row">
                        <div class="col-xs-10 ">
                            <h3>{{ $vf['info']->nombre_faqcat }}</h3>
                        </div>
                        <div class="col-xs-2 text-right">
                            <a href="/admin/faqs/{{ $vf['info']->lang_faqcat }}/categories/edit/{{ $vf['info']->cod_faqcat }}"
                                class="btn btn-default"><span class="fa fa-edit"></span></a>
                            @if (count($vf['items']) == 0)
                                <a onclick="javascript:deleteFaqCat({{ $vf['info']->cod_faqcat }})"
                                    class="btn btn-default"><span class="fa fa-close"></span></a>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- FAQ Cat whith parent -->
                <div class="card-body sortable" data-child="s2" data-isCategory=true>
                    @foreach ($vf['items'] as $k => $v)
                        <div class="s2" id="{{ $v->cod_faqcat }}">
                            <div class="row"
                                style="border:#CCC 1px solid;background:#EEE;margin:0 15px;padding:10px;cursor:pointer;"
                                onclick='javascript:$("#plega{{ $k }}").toggle();'>
                                <div class="col-xs-10">
                                    <big>{{ $v->nombre_faqcat }}&nbsp;&nbsp; ( <?php
										if (isset($data['faqs'][$k])) {
											echo count($data['faqs'][$k]);
										} else {
											echo '0';
										}
										?>)
                                    </big>
                                </div>
                                <div class="col-xs-2 text-right">
                                    @if ($v->parent_faqcat)
                                        <a href="/admin/faqs/{{ $v->lang_faqcat }}/edit?cat={{ $v->cod_faqcat }}"
                                            class="btn btn-default">Nueva faq</a>
                                    @endif
                                    <a href="/admin/faqs/{{ $v->lang_faqcat }}/categories/edit/{{ $v->cod_faqcat }}"
                                        class="btn btn-default"><span class="fa fa-edit"></span></a>
                                    @if (!isset($data['faqs'][$k]) || count($data['faqs'][$k]) == 0)
                                        <a href="javascript:deleteFaqCat({{ $v->cod_faqcat }})"
                                            class="btn btn-default"><span class="fa fa-close"></span></a>
                                    @endif
                                </div>
                            </div>


                            <!-- FAQs -->
                            <div class="row sortable" data-child="s3" data-isCategory=false
                                style="margin:0 15px;display:none" id="plega{{ $k }}">
                                @if (isset($data['faqs'][$k]))

                                    @foreach ($data['faqs'][$k] as $item)

                                        <div class="row s3" id="{{ $item->cod_faq }}"
                                            style="border:#CCC 1px solid;margin:0 10px;padding:10px;">
                                            <div class="col-xs-9" style="padding:15px">
                                                {{ $item->titulo_faq }}
                                            </div>
                                            <div class="col-xs-3 text-right" style="padding:10px">
                                                <a href="/admin/faqs/{{ $item->lang_faq }}/edit/{{ $item->cod_faq }}"
                                                    class="btn btn-primary"><span class="fa fa-edit"></span></a>
                                                <a href="javascript:deleteFaq({{ $item->cod_faq }})"
                                                    class="btn btn-danger"><span class="fa fa-close"></span></a>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>

                                    @endforeach

                                @endif
                            </div>
                        </div>




                    @endforeach
                    <br>

                </div>
            </div>

            <br>


        @endforeach

    @endif
</div>
