

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

   

<section class="blog_bread">
    <?php 
    $bread[] = array("name" => trans(\Config::get('app.theme').'-app.blog.name'), 'url'=>\Routing::slugSeo('blog') );
    $titulo_post = $data['news']->titulo_web_blog_lang;
    $bread[] = array("name" => $titulo_post) ;
    ?>
@include('includes.breadcrumb')
</section>

<style>
    <?= "@import url('https://fonts.googleapis.com/css?family=Playfair+Display:700'); " ?>

</style>
<meta name="twitter:site" content="@flickr" />
<meta name="twitter:title" content="Small Island Developing States Photo Submission" />
<meta name="twitter:description" content="View the album on Flickr." />
<meta name="twitter:image" content="https://farm6.staticflickr.com/5510/14338202952_93595258ff_z.jpg" />



<section class="blog_title_content">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 blog_title_container">

               <div class="article-title text-center"><h1>{{ $data['news']->titulo_web_blog_lang }}</h1></div>
                
                
            </div>
            <div class="col-xs-12 text-center">
                <div class="date-post-principal article-data text-center">
                    <?php  

                        $fecha = strftime('%d %b %Y',strtotime($data['news']->publication_date_web_blog));
                                    
                        if(\App::getLocale() != 'en'){
                            $array_fecha = explode(" ",$fecha);
                            $array_fecha[1] = \Tools::get_month_lang($array_fecha[1],trans(\Config::get('app.theme')."-app.global.month_large"));
                            $fecha = $array_fecha[0].' '.$array_fecha[1].' '.$array_fecha[2];
                        }

                        ?>    
                    <p>{{ $fecha }}</p>
                </div>
            </div>
            <div class="col-xs-12">

                <div class="col-md-12 col-xs-12">
                <div class="post_body_image">
                        
                        @if(!empty($data['news']->video_web_blog_lang) && str_is('*youtu*',$data['news']->video_web_blog_lang) == true)
                            <?php
                                $cod_video = $data['news']->video_web_blog_lang;
                                $cod_video = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $cod_video);
                            ?>
                            <iframe class="video_post" style="width: 100%;min-height: 462px;" width="560" height="315" src="{{ $cod_video }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        @else
                            <img class="img-responsive" src="{{ $data['news']->img_web_blog }}" >
                            
                        @endif
                        
                    </div>
            </div>
                </div>
            
        </div>
    </div>
</section>
<section class="article-body">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                 <ul class="share-article">
                        <li class="facebook">
                            <a title="{{ trans(\Config::get('app.theme').'-app.lot.share_on') }} Facebook" href='javascript:abrirNuevaVentana("http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>")'>
                                <svg xmlns="https://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 44 44">
                                    <g fill="none" fill-rule="evenodd">
                                        <ellipse class="border"  cx="21.811" cy="22.579" stroke="#AA966E" rx="20.632" ry="20.263"/>
                                        <path class="red" fill="#AA966E" d="M24.522 14.335c-1.214 0-2.185.359-2.915 1.065-.73.695-1.095 1.69-1.095 2.976v2.258h-2.685v3.057h2.685v7.85h3.22v-7.85h2.674l.41-3.057h-3.083v-1.957c0-.498.106-.869.316-1.123.21-.243.617-.37 1.22-.37h1.654v-2.722c-.57-.093-1.369-.127-2.4-.127z"/>
                                    </g>
                                </svg>
                            </a>
                        </li>
                        <li class="twitter">
                            <a title="{{ trans(\Config::get('app.theme').'-app.lot.share_on') }} Twitter" href='javascript:abrirNuevaVentana("http://twitter.com/share?text=<?= $data['news']->titulo_web_blog_lang?> <?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?> &url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>")'>
                                <svg xmlns="https://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 44 44">
                                    <g fill="none" fill-rule="evenodd" transform="translate(.25)">
                                        <ellipse class="border" cx="21.811" cy="22.579" stroke="#AA966E" rx="20.632" ry="20.263"/>
                                        <path class="red" fill="#AA966E" d="M28.387 18.295a3.22 3.22 0 0 0 1.495-1.841 6.877 6.877 0 0 1-2.159.81c-.673-.706-1.5-1.053-2.484-1.053-.94 0-1.741.324-2.405.972a3.2 3.2 0 0 0-.995 2.362c0 .255.028.51.085.764a9.487 9.487 0 0 1-3.91-1.03 9.632 9.632 0 0 1-3.1-2.455 3.197 3.197 0 0 0-.463 1.68c0 .578.136 1.1.41 1.597.273.486.642.88 1.105 1.181a3.54 3.54 0 0 1-1.537-.417v.035c0 .81.259 1.517.774 2.13a3.35 3.35 0 0 0 1.952 1.147 3.354 3.354 0 0 1-.895.116c-.195 0-.41-.012-.641-.047.217.66.617 1.216 1.2 1.645a3.438 3.438 0 0 0 1.978.671c-1.235.95-2.642 1.424-4.22 1.424-.302 0-.576-.011-.822-.046 1.58 1.007 3.32 1.505 5.222 1.505 1.207 0 2.34-.185 3.4-.567 1.058-.37 1.964-.88 2.715-1.505a10.286 10.286 0 0 0 1.943-2.177 9.706 9.706 0 0 0 1.215-2.57 9.319 9.319 0 0 0 .39-3.115 7.075 7.075 0 0 0 1.704-1.726 7.19 7.19 0 0 1-1.957.51z"/>
                                    </g>
                                </svg>
                            </a>
                        </li>
                            
                            <li class="gplus">
                                <a title="{{ trans(\Config::get('app.theme').'-app.lot.share_on') }} google+" href='javascript:abrirNuevaVentana("https://plus.google.com/share?url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>")'>
                                    <svg xmlns="https://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 45 44">
                                        <g fill="none" fill-rule="evenodd" transform="translate(.75)">
                                            <ellipse class="border" cx="21.811" cy="22.579" stroke="#AA966E" rx="20.632" ry="20.263"/>
                                            <path class="red" fill="#AA966E" d="M22.46 21.884c.171-.22.38-.44.625-.637.246-.208.49-.428.732-.671.242-.243.45-.568.62-.961.173-.394.259-.846.259-1.343 0-.626-.133-1.17-.4-1.656-.266-.475-.66-.938-1.179-1.378h1.38l1.452-.903h-4.4c-.786 0-1.55.116-2.29.324-.74.232-1.38.567-1.921 1.019-.442.37-.789.822-1.042 1.355a3.788 3.788 0 0 0-.379 1.644c0 1.019.356 1.864 1.068 2.524.712.672 1.588.996 2.627.996.155 0 .393-.012.716-.035-.148.336-.222.625-.222.88 0 .278.055.533.164.753.108.22.275.474.5.764-1.916.127-3.333.463-4.253 1.03-.52.325-.935.718-1.247 1.193a2.775 2.775 0 0 0-.468 1.552c0 .486.13.938.389 1.354.4.66 1.002 1.135 1.805 1.425a7.89 7.89 0 0 0 2.595.428c1.115 0 2.16-.197 3.137-.602.975-.394 1.715-1.007 2.22-1.853a3.46 3.46 0 0 0 .506-1.783c0-.544-.112-1.03-.337-1.47a3.567 3.567 0 0 0-.81-1.077 12.97 12.97 0 0 0-.953-.764 6.178 6.178 0 0 1-.816-.695c-.225-.243-.337-.486-.337-.73 0-.243.086-.463.258-.683zm-2.811-.579a2.774 2.774 0 0 1-.79-.694 4.333 4.333 0 0 1-.547-.962 5.129 5.129 0 0 1-.332-1.065 5.042 5.042 0 0 1-.105-1.007c0-.695.164-1.24.494-1.644.176-.232.406-.417.69-.545a1.96 1.96 0 0 1 2.1.22c.372.278.668.637.89 1.066.22.428.39.88.51 1.343.12.463.18.891.18 1.285 0 .706-.187 1.25-.56 1.644a2.067 2.067 0 0 1-.694.452c-.28.116-.554.174-.82.174-.366 0-.704-.093-1.016-.267zm-.306 9.148a5.303 5.303 0 0 1-1.252-.464 2.443 2.443 0 0 1-.963-.856 2.23 2.23 0 0 1-.369-1.274c0-.405.095-.776.284-1.088.19-.325.43-.58.722-.776a4.067 4.067 0 0 1 1.02-.475 6.08 6.08 0 0 1 1.132-.255 8.764 8.764 0 0 1 1.105-.08c.225 0 .393.01.506.022.021.012.148.105.384.267.235.173.38.278.436.312.057.047.184.14.385.301.2.162.335.278.405.36.07.08.174.196.31.358.137.162.233.301.285.429.053.127.103.266.153.44.048.162.073.335.073.51 0 .416-.097.798-.289 1.122-.193.325-.45.58-.769.753-.32.174-.667.313-1.042.405a4.879 4.879 0 0 1-1.174.128 6.48 6.48 0 0 1-1.342-.14zm9.868-11.162h-1.104v2.246h-2.232v1.123h2.232v2.258h1.104V22.66h2.243v-1.123H29.21V19.29z"/>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                            <li class="gplus">
                                <a title="{{ trans(\Config::get('app.theme').'-app.lot.share_on') }} pinterest" href=javascript:abrirNuevaVentana("https://pinterest.com/pin/create/button/?media=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>")>
                                    <svg xmlns="https://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 45 44">
                                        <g fill="none" fill-rule="evenodd" transform="translate(.75)">
                                            <ellipse class="border" cx="21.811" cy="22.579" stroke="#AA966E" rx="20.632" ry="20.263"/>
                                            <path class="red" fill="#AA966E" d="M21.4,14c-4.9,0-7.4,3.4-7.4,6.2c0,0.5,0.1,1,0.2,1.5c0.3,1.1,0.9,1.9,1.9,2.3c0.2,0.1,0.4,0,0.5-0.2
                                                    c0-0.2,0.2-0.6,0.2-0.8c0.1-0.3,0-0.3-0.1-0.6c-0.3-0.3-0.5-0.7-0.6-1C16,21.1,16,20.8,16,20.5c0-2.5,2-4.8,5.1-4.8
                                                    c2.8,0,4.3,1.6,4.3,3.8c0,0.6-0.1,1.2-0.2,1.8c-0.4,2-1.6,3.5-3.1,3.5c-1.1,0-1.9-0.9-1.6-1.9c0.1-0.6,0.4-1.2,0.5-1.8
                                                    c0.2-0.6,0.4-1.3,0.4-1.7c0-0.8-0.5-1.5-1.4-1.5c-1.1,0-2,1.1-2,2.6c0,0.3,0,0.5,0.1,0.7c0.1,0.5,0.3,0.8,0.3,0.8s-1.1,4.6-1.3,5.4
                                                    c-0.4,1.6-0.1,3.6,0,3.8c0,0.1,0.2,0.1,0.2,0.1c0.1-0.1,1.4-1.7,1.9-3.3c0.1-0.4,0.7-2.7,0.7-2.7c0.4,0.7,1.4,1.2,2.5,1.2
                                                    c2.7,0,4.7-1.9,5.3-4.8c0.2-0.7,0.2-1.4,0.2-2.1C27.9,16.8,25.3,14,21.4,14"/>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                    </ul>
             <?php 
                        $data['news']->texto_web_blog_lang = str_replace("a:visited", ".post_body a:visited", $data['news']->texto_web_blog_lang);
                        $data['news']->texto_web_blog_lang = str_replace("a:link", ".post_body a:link", $data['news']->texto_web_blog_lang);
                        $data['news']->texto_web_blog_lang = str_replace("<style>", "<style>/*", $data['news']->texto_web_blog_lang);
                        $data['news']->texto_web_blog_lang = str_replace("</style>", "*/</style>", $data['news']->texto_web_blog_lang);
 
                        
                        ?>
                        <p class="cuerpo-del-articulos"><?= $data['news']->texto_web_blog_lang ?></p>       
        </div>
            
        </div>
    </div>
    
</section>
<section class="categ-related">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <div class="article-categoria-titulo">
                    {{ trans(\Config::get('app.theme').'-app.blog.cat_related') }}:
                </div>
            <div class="categorias-relacionadas-items">
                
                    @foreach($data['categorys_web'] as $rel_cat)       
                    <a class="categoria-sidebar item-categoria-article" href="<?= \Routing::translateSeo('subastas').$rel_cat->key_ortsec0 ?>" role="button">{{ $rel_cat->des_ortsec0 }}</a>                   
                        @endforeach
                    
                </div>
            </div>
        </div>
    </div>
</section>


<section class="entradas-realacionadas">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="article-categoria-titulo">
                    {{ trans(\Config::get('app.theme').'-app.blog.post_related') }}:
                </div>
                <div class="entradas-relacionadas-lista">
                    @foreach($data['relationship_new'] as $rel_link)               
                                <?php 
                                    $url = \Routing::slugSeo('blog').'/'.$data['categorys'][$rel_link->primary_category_web_blog]->url_category_blog_lang.'/'.$rel_link->url_web_blog_lang
                                ?>
                    <div class="col-md-4 entrada-relacionada-item col-xs-6">
                        <div class="entrada-relacionada-title">
                            {{ $rel_link->titulo_web_blog_lang }}
                        </div>
                        <div class="img-related-post" style="margin: 10px 0 20px 0">
                            <img class="img-responsive" src="{{ $rel_link->img_web_blog }}" >
                        </div>
                        <div class="button-post">
                                <a href="{{ $url }}" role="button"><?= trans(\Config::get('app.theme').'-app.blog.more') ?></a>
                        </div>
                    </div>
                                   
                                        

                        @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    
    <?php
            $sub_categories_web = str_replace(",","','",$data['news']->lot_sub_categories_web_blog);

            $key = "relacionados_noticia";
            $replace = array(
                  'sec'=>$sub_categories_web,
                  'lang' => Config::get('app.language_complete')[Config::get('app.locale')] ,
                'emp' => Config::get('app.emp') ,
                      );
        ?>
        var replace = <?= json_encode($replace) ?>;
        var key ="<?= $key ?>";
    $( document ).ready(function() {
        
        ajax_carousel(key,replace); 
        if($('.post_recents_list ul li').length < 4){
            $('.post_recents_button').hide();
        }
        
        $('.post_recents_button').on('click', function(){
                $('.post_recents_list ul li').toggleClass('active');
            
                if($(this).attr('data-open') === 'open'){
                    $(this).text('Ver más')
                    $(this).attr('data-open', 'close')

                }else{
                    $(this).text('Ver menos')
                    $(this).attr('data-open', 'open')
                }
            });
        
    });
    
    
    


</script>
@stop
