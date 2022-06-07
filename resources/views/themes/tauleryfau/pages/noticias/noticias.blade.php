

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<style>
@import url('https://fonts.googleapis.com/css?family=Noto+Serif+KR:400,500,700');
</style>
<section class="blog_bread">
     <?php 
        $bread = array();
        $bread[] = array("name" => trans(\Config::get('app.theme').'-app.blog.name'), 'url' => \Routing::slugSeo('blog'));
        if(!empty ($data['categ'])){
            $categoria = $data['categ']->title_category_blog_lang;
            $bread[] = array("name" => $categoria );
        }
    ?>
@include('includes.breadcrumb')
</section>
<section class="blog_title_content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 blog_title_container">
                <div class="linea">
                <div class="linea-content"></div>
            </div>
                @if(empty ($data['categ']))
                <div class="blog_title text-center"><?= trans(\Config::get('app.theme').'-app.blog.principal_title') ?></div>
                @else
                <div class="blog_title text-center">{{ $data['categ']->title_category_blog_lang }}</div>
                @endif
            <div class="linea">
                <div class="linea-content"></div>
            </div>
            </div>
            
        </div>
    </div>
</section>

<section class="post_content">
    <div class="container">
            @if(count($data['noticias']) != 0)
        <div class="row">
                <?php $url =\Routing::slugSeo('blog').'/'.$data['categorys'][$data['noticias'][0]->primary_category_web_blog]->url_category_blog_lang.'/'.$data['noticias'][0]->url_web_blog_lang ?>
            <div class="col-sm-12 col-xs-12 primer-post ">
                <div class="col-xs-12 col-md-7 hidden-md hidden-lg">
                    <img alt="{{$data['noticias'][0]->titulo_web_blog_lang}}" class="img-responsive" src="{{$data['noticias'][0]->img_web_blog}}">
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="title-post-principal">
                        <p>{{$data['noticias'][0]->titulo_web_blog_lang}}</p>
                    </div>
                    <div class="date-post-principal">
                        <?php
                                    $fecha = strftime('%d %b %Y',strtotime($data['noticias'][0]->publication_date_web_blog));
                                    
                                    if(\App::getLocale() != 'en'){
                                        $array_fecha = explode(" ",$fecha);
                                        $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                                        $fecha = $array_fecha[0].' '.$array_fecha[1].' '.$array_fecha[2];
                                    }
                                    ?>    
                        <p>{{ $fecha }}</p>
                    </div>
                    <div class="resumen resumen-principal">
                         <?php 
                        $data['noticias'][0]->texto_web_blog_lang = str_replace("a:visited", ".post_body a:visited", $data['noticias'][0]->texto_web_blog_lang);
                        $data['noticias'][0]->texto_web_blog_lang = str_replace("a:link", ".post_body a:link", $data['noticias'][0]->texto_web_blog_lang);
                       $data['noticias'][0]->texto_web_blog_lang = str_replace("<style>", "<style>/*", $data['noticias'][0]->texto_web_blog_lang);
                        $data['noticias'][0]->texto_web_blog_lang = str_replace("</style>", "*/</style>", $data['noticias'][0]->texto_web_blog_lang);

                        
                        ?>
                        <?= $data['noticias'][0]->texto_web_blog_lang ?>
                    </div>
                    <div class="button-post">
                       <a href="{{ $url }}"><?= trans(\Config::get('app.theme').'-app.blog.more') ?></a>
                    </div>
                </div>
                <div class="col-xs-12 col-md-7 hidden-xs hidden-sm">
                    <img alt="{{$data['noticias'][0]->titulo_web_blog_lang}}" class="img-responsive" src="{{$data['noticias'][0]->img_web_blog}}">
                </div>
            </div>
            
    </div>
            @endif
        <div class="row princiapl-body">
            <div class="col-md-9 col-xs-12 post-section">
                @if(count($data['noticias']) == 0)
            <div class="alert alert-danger col-xs-12">
                <?= trans(\Config::get('app.theme').'-app.blog.not_result') ?>
            </div>

            @else
                @foreach($data['noticias'] as $key => $noticias)
                <?php $url = \Routing::slugSeo('blog').'/'.$data['categorys'][$noticias->primary_category_web_blog]->url_category_blog_lang.'/'.$noticias->url_web_blog_lang ?>
             @if($key != 0)
                    <div class="col-xs-12 no-padding normal-post">
                        <div class="col-md-5 col-xs-12 no-padding">
                            <a href="{{ $url }}"><img alt="{{$noticias->titulo_web_blog_lang}}" class="img-responsive" src="{{$noticias->img_web_blog}}"></a>
                        </div>
                        <div class="col-md-7 col-xs-12 no-padding post-nomal-content">
                            <div class="post-normal-title">
                                <p>{{$noticias->titulo_web_blog_lang}}</p>
                            </div>  
                            <div class="post-normal-date">
                                <?php
                                    $fecha = strftime('%d %b %Y',strtotime($noticias->publication_date_web_blog));
                                    
                                    if(\App::getLocale() != 'en'){
                                        $array_fecha = explode(" ",$fecha);
                                        $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                                        $fecha = $array_fecha[0].' '.$array_fecha[1].' '.$array_fecha[2];
                                    }
                                    ?>    
                                <p>{{ $fecha }}</p>
                            </div>
                          <div class="resumen">
                         <?php 
                            $noticias->texto_web_blog_lang = str_replace("a:visited", ".post_body a:visited", $noticias->texto_web_blog_lang);
                            $noticias->texto_web_blog_lang = str_replace("a:link", ".post_body a:link",$noticias->texto_web_blog_lang);
                            $noticias->texto_web_blog_lang = str_replace("<style>", "<style>/*", $noticias->texto_web_blog_lang);
                            $noticias->texto_web_blog_lang = str_replace("</style>", "*/</style>", $noticias->texto_web_blog_lang);
                            ?>
                            <?= $noticias->texto_web_blog_lang ?> 
                        </div>
                            <div class="button-post">
                                <a href="{{ $url }}"><?= trans(\Config::get('app.theme').'-app.blog.more') ?></a>
                        </div>
                </div>
            </div>
@endif
                @endforeach
            @endif
                
           
        </div>
             <div class="col-md-3 col-xs-12 sidebar">
                 <div class="categorias-sidebar">
                     <div class="sidebar-title-content">
                          <div class="sidebar-linea">
                            <div class="linea-content"></div>
                        </div>
                        <div class="sidebar_title">Categorias</div>
                        <div class="sidebar-linea">
                            <div class="linea-content"></div>
                        </div>
                    </div>
                    <div class="categorias-sidebar-lista">
                        <a class="categoria-sidebar {{ $data['categ'] ? ' ' : 'active' }}" href="<?= \Routing::slugSeo('blog').'/'?>" role="button"><?= trans(\Config::get('app.theme').'-app.blog.principal_cat_link') ?></a>
                    @foreach($data['categorys'] as $category)
                            @if((!empty ($data['categ']) && ($category->title_category_blog_lang == $data['categ']->title_category_blog_lang )))                
                            <a href="<?= \Routing::slugSeo('blog').'/'.$category->url_category_blog_lang ?>" role="button" class="active categoria-sidebar">
                                {{ $data['categ']->name_category_blog_lang }}
                            </a>
                            @else
                            <a href="<?= \Routing::slugSeo('blog').'/'.$category->url_category_blog_lang ?>" role="button" class="categoria-sidebar">
                                {{$category->name_category_blog_lang}}
                            </a>
                            @endif
                        </li>   
                    @endforeach
                    </div>
                 </div>
            </div>
        </div>
    </div>
</section>

  <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center pagination_blog">

                 @if(count($data['noticias']) != 0)
                    <?php echo $data['noticias']->links(); ?>
                 @endif
            </div>
        </div>
    </div> 
    
<section>
<div id='seo_content' class='container content'>
    <div class='row'>
        <div class="col-sm-12">
            @if(empty ($data['categ']))
                <?php
                    $key = "info_h2_blog_".strtoupper(Config::get('app.locale'));
                    $html="{html}";
                    $content = \Tools::slider($key, $html);
                ?>
                <?= $content ?>
            @else
                <?=  $data['categ']->metacont_category_blog_lang ?>
            @endif
        </div>
    </div>
</div>
</section>

<script>

    
    
 /*   funcion para categorias con scroll horizontal
(function ($) {
    var scrollCategories = {
        init: function () {
            this.cache();
            this.bindEvents();
        },
        cache: function () {
            this.btnR = $('.scroll-controller.right')
            this.btnL = $('.scroll-controller.left')
            this.scroll = 0;
            this.container = $('.blog_categories_list')
            this.scrollTotal =  $('.blog_categories_list')[0].scrollWidth - 250;
        },
        move: function (e) {

           if($(e.currentTarget).hasClass('left')){
               if(this.scroll > 0){
                    this.scroll = this.scroll - (this.scrollTotal /10);
                    if(this.scroll < 0 ){
                        this.scroll = 0;
                    }else{
                        //$(e.currentTarget).find('i').hide()
                    }
               }
               
           }else{
                if(this.scroll < this.scrollTotal){
                    
                    this.scroll = this.scroll + (this.scrollTotal /10);
                    if(this.scroll > this.scrollTotal ){
                        this.scroll = this.scrollTotal;
                    }
               }
           }
           this.moveScroll()

            
        },
        moveScroll: function () {
                console.log(this.scroll)
                this.container.animate({
                    scrollLeft: this.scroll
                },200)
            },

        activeBtn: function(){
            

        },
        disabledBtn: function(){
            
            
        },
        bindEvents: function () {
            //this.btnL.hide()
            this.btnR.on('click', this.move.bind(this));
            this.btnL.on('click', this.move.bind(this));
        }
    };

    scrollCategories.init();

})($);

*/
    $(document).ready(function(){
        $('.resumen').each(function (){
            var str = $(this).text();
            var res = str.replace("[*CITA*]","");
            console.log(res);
            var str = $(this).text(str);
        });
        
    });

</script>




@stop