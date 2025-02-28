@extends('layouts.default')

@section('title')
    {{ trans('web.foot.faq') }}
@stop

@section('content')
    @php
        $bread[] = [
            'name' => trans('web.foot.faq'),
            'url' => URL::current(),
            'title' => 'title',
        ];

        $categories = $data['cats']->where('parent_faqcat', 0);
        $subCategories = $data['cats']->where('parent_faqcat', '!=', 0)->groupBy('parent_faqcat');
        $questions = $data['items']->groupBy('cod_faqcat');
    @endphp

    <main class="faqs">
        <div class="container">
            @include('includes.breadcrumb')
            <h1>{{ trans('web.foot.faq') }}</h1>
        </div>

        <div class="container mt-3" id="faq">

            <div class="row row-cols-1 row-cols-lg-3">

                <div class="col">
                    <h4>{{ trans("web.faq.select_category") }}</h4>
                    <hr>
                    <div class="list-group list-group-flush" role="tablist">
                        @foreach ($categories as $item)
                            <a id="parent-{{ $item->cod_faqcat }}" data-bs-toggle="list" href="#list-{{ $item->cod_faqcat }}"
                                role="tab" aria-controls="list-{{ $item->cod_faqcat }}" @class([
                                    'list-group-item list-group-item-action d-flex justify-content-between align-items-center',
                                    'active' => $loop->first,
                                ])>
                                {{ $item->nombre_faqcat }}
                                <span>></span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col tab-content" id="tabSubcategory" role="tabpanel">
                    <h4>{{ trans("web.faq.select_sub_category") }}</h4>
                    <hr>

                    @foreach ($subCategories as $parentId => $items)
                        <div id="list-{{ $parentId }}" role="tablist" aria-labelledby="parent-{{ $parentId }}"
                            @class([
                                'list-group list-group-flush tab-pane fade',
                                'show active' => $loop->first,
                            ])>
                            @foreach ($items as $item)
                                <a id="subcategory-{{ $item->cod_faqcat }}" data-bs-toggle="list"
                                    href="#question-{{ $item->cod_faqcat }}" role="tab"
                                    aria-controls="question-{{ $item->cod_faqcat }}" @class([
                                        'list-group-item list-group-item-action d-flex justify-content-between align-items-center',
                                    ])
                                    {{-- onclick="muestraFaq({{ $item->cod_faqcat }})" --}}>
                                    {{ $item->nombre_faqcat }}
                                    <span>></span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach


                </div>

                <div class="col tab-content">
                    <h4>{{ trans("web.faq.select_question") }}</h4>
                    <hr>

                    @foreach ($questions as $parentId => $items)
                        <div id="question-{{ $parentId }}" role="tabpanel"
                            aria-labelledby="subcategory-{{ $parentId }}" @class(['list-group list-group-flush tab-pane fade'])>
                            @foreach ($items as $item)
                                <div class="parentFaq parentFaq{{ $item->cod_faqcat }}">
                                    <a class="question" href="javascript:FaqshowContent('faq{{ $item->cod_faq }}')">
                                        <span>+</span>
                                        {{ $item->titulo_faq }}
                                    </a>
                                    <div class="faq" id="faq{{ $item->cod_faq }}">
                                        {!! nl2br($item->desc_faq) !!}
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>

            </div>

        </div>
    </main>

    <script>
        const triggerTabList = document.querySelectorAll('#tabSubcategory a')
        triggerTabList.forEach(triggerEl => {
            const tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', event => {
                event.preventDefault()
				triggerTabList.forEach(triggerEle => {
					const tabTriggerR = new bootstrap.Tab(triggerEl)
					tabTriggerR.hide();
					//triggerEle.classList.remove("active");
				})
                tabTrigger.show()
            })
        })
    </script>
@stop
