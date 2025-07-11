@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">
		</header>

	<div id="cms">
            <div class="row">
                        <form id="save_category_blog">
                             <div class="col-md-10">
                                 <?php
                                 $categorys = $data['categorys'];
                                 $first_categ = head($categorys);

                                         ?>
                                <section class="panel">
                                            <input type="hidden" class="id_input" name="id"  value="<?= empty($first_categ)?'0':$first_categ->id_category_blog?>">
                                            <input type="hidden"  name="orden"  value="<?=empty($first_categ)?'0':$first_categ->id_category_blog?>">
                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-4">
                                                                    <p>{{ trans('admin-app.placeholder.nombre') }}</p>
                                                                        <input required type="text" name="title" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="{{empty($first_categ)? '' : $first_categ->title_category_blog}}">

                                                                </div>
                                                        </div>
                                                </div>

                                </section>
                                 <section class="panel">
                                    <div class="panel-body">
                                        <div class="tabs tabs-bottom tabs-primary">
                                            <ul class="nav nav-tabs nav-justified">

                                                 {{$i = true}}
                                                 @foreach($data['idiomes'] as $idiomes => $keylang)
                                                    <li class="<?= ($i == true)? 'active' : ''; ?>" >
                                                            <a href="#{{$idiomes}}" data-toggle="tab" class="text-center"> {{$keylang}}</a>
                                                    </li>
                                                 {{$i = false}}
                                                 @endforeach
                                            </ul>
                                            <div class="tab-content">
                                                    <?php $i = true ?>
                                                    @foreach($data['idiomes'] as $idiomes => $keylang)
                                                       <div id="{{$idiomes}}" class="tab-pane <?= ($i == true)? 'active' : ''; ?>">
                                                           <div class='row'>
                                                               <?php
                                                                    $blog_lang = NULL;
                                                                     if(!empty($data['categorys']) && !empty($data['categorys'][strtoupper($idiomes)])) {
                                                                         $blog_lang = $data['categorys'][strtoupper($idiomes)];
                                                                     }
                                                               ?>
                                                                        <div class="col-md-6">
                                                                             <p>{{ trans('admin-app.placeholder.nombre') }}</p>
                                                                             <input required maxlength="50"  type="text" name="name_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.nombre') }}" class="form-control" value="<?= !empty($blog_lang)?$blog_lang->name_category_blog_lang:"" ?>">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                             <p>{{ trans('admin-app.placeholder.titulo') }} (H1)</p>
                                                                             <input required maxlength="50"  type="text" name="title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.titulo') }}" class="form-control" value="<?= !empty($blog_lang)?$blog_lang->title_category_blog_lang:"" ?>">
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <p>{{ trans('admin-app.placeholder.link') }}</p>
                                                                            <input required maxlength="255"  type="text" name="url_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.link') }}" class="form-control" value="<?= !empty($blog_lang)?$blog_lang->url_category_blog_lang:"" ?>">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <p>{{ trans('admin-app.placeholder.meta_title') }}</p>
                                                                            <input maxlength="67"  type="text" name="meta_title_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.meta_title') }}" class="form-control" value="<?= !empty($blog_lang)?$blog_lang->metatit_category_blog_lang:"" ?>">
                                                                       </div>
                                                                       <div class="col-lg-6">
                                                                           <p>{{ trans('admin-app.placeholder.meta_description') }}</p>
                                                                           <input maxlength="155"  type="text" name="meta_desc_{{strtoupper($idiomes)}}" placeholder="{{ trans('admin-app.placeholder.meta_description') }}" value="<?= !empty($blog_lang)?$blog_lang->metades_category_blog_lang:'' ?>"  class="form-control">
                                                                        </div>
                                                                    <div class="row form-group">
                                                                    <div class="col-md-12 ml-15"><p>{{ trans('admin-app.placeholder.meta_content') }}</p></div>
                                                                    <div class="col-md-12" >
                                                                        <div id="meta_cont_{{strtoupper($idiomes)}}" class="summernote summernote_descrip" data-plugin-summernote data-plugin-options="{ 'height': 900, 'codemirror': { 'theme': 'ambiance' } }" placeholder="{{ trans('admin-app.placeholder.contenido') }}" class="form-control" rows="5">
                                                                            <?= !empty($blog_lang)?$blog_lang->metacont_category_blog_lang:"" ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                           </div>
                                                       </div>
                                                       {{$i = false}}
                                                    @endforeach
                                            </div>
                                       </div>
                                    </div>
                                 </section>
                             </div>
                            <div class="col-md-2">
                                <section>

                                                <div class="panel-body">
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                       <div class="checkbox-custom checkbox-default">
                                                                           <?php
                                                                            if((empty($first_categ)) ||(!empty($first_categ->enable_category_blog) && $first_categ->enable_category_blog == 1) ){
                                                                                $enable= 1;
                                                                            }else{
                                                                              $enable= 0;
                                                                            }
                                                                           ?>
                                                                                <input name="enabled" type="checkbox"  id="checkboxExample3" <?= ($enable == 1)? 'checked' : '' ; ?>>
                                                                                <label for="checkboxExample3">{{ trans('admin-app.title.activated_desactivated') }}</label>
                                                                        </div>
                                                                </div>
                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>
                                                        <div class="row form-group">
                                                                <div class="col-lg-12">
                                                                    <input type='button' class="mb-xs mt-xs mr-xs btn btn-lg btn-primary save_category_blog" value='{{ trans('admin-app.title.save') }}'></input>
                                                                </div>
                                                                 <div class="col-lg-12">
                                                                    <a href="/admin/category-blog" class="mb-xs mt-xs mr-xs btn btn-lg btn-primary">{{ trans('admin-app.title.return_listado') }}</a>
                                                                </div>

                                                                <div class="mb-md hidden-lg hidden-xl"></div>

                                                        </div>

                                                </div>

                                </section>
                            </div>
                        </form>

        </div>

	</div>
</section>

@stop
