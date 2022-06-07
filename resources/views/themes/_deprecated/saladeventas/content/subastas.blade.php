<?php
    $sessions = array();
    $finished = null;
    if(!empty($_GET['finished'])){
        $finished = $_GET['finished'];
        foreach($data['auction_list'] as $key => $sub_finished){
            if(strtotime($sub_finished->session_end) <= time() && $_GET['finished'] == 'false'){
                unset($data['auction_list'][$key]);
            }
            elseif(strtotime($sub_finished->session_end) > time() && $_GET['finished'] == 'true'){
                unset($data['auction_list'][$key]);
            }
        }
    }
    foreach($data['auction_list'] as $key => $sub_finished){
        $sessions[$sub_finished->cod_sub][] = $sub_finished->id_auc_sessions;
    }
?>
<div class="container">
        <div class="row">
             <div class="col-xs-12 col-sm-12">
                <h1 class="titlePage"> {{ trans(\Config::get('app.theme').'-app.subastas.auctions') }}</h1>
            </div>
        </div>
</div>
@if (count($sessions) != 1) 
<div class="container">
    <div class="row">
        @foreach ($data['auction_list'] as  $subasta)
        
            <?php 

                $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                if(1!=1&&count($indices) > 0 ){       
                    $url_lotes=\Routing::translateSeo('indice-subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                }else{
                    $url_lotes=\Routing::translateSeo('subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                    if($finished == true && $subasta->tipo_sub == 'W' && ($subasta->subc_sub == 'S' ||   $subasta->subc_sub == 'A') && strtotime($subasta->session_start) < strtotime("now") ){
                        $url_lotes.= '?order=fbuy';
                    }
                }
                $url_tiempo_real=\Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                $url_subasta=\Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);

            ?>
            <div class="col-xs-12 col-sm-4 col-lg-3">
                <div class="item_subasta">
                    @if(strtotime($subasta->session_start) > strtotime('2017-05-01'))
                        <a title="{{ $subasta->name }}" href="<?= $url_lotes?>">
                    @endif    
                        <div class="img-lot">
                            <img 
                                src="/img/load/subasta_medium/SESSION_{{ $subasta->file_code }}.jpg" 
                                alt="{{ $subasta->name }}" 
                                class="img-responsive" 
                            />                            
                        </div>
                    @if(strtotime($subasta->session_start) > strtotime('2017-05-01'))
                    </a>
                    @endif
                    <div class="item_subasta_item text-center">
                        {{ $subasta->name }}
                    </div>
                    @if(strtotime($subasta->session_start) > strtotime('2017-05-01'))
                        <a title="{{ $subasta->name }}" href="{{ $url_lotes }}" class=" btn btn-lotes btn-color">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                        <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-subasta">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
                    @endif
                    @if( file_exists(\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat')))
                        <p class="text-center " style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                            <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_catalog') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat')}}">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</a> <br>
                        </p>
                    @endif
                    @if( file_exists(\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'adj')))
                        <p class="text-center "  style="background-color:#ecedef;padding: 5px 0; margin-top:10px">
                            <a title="{{ trans(\Config::get('app.theme').'-app.grid.pdf_adj') }}" target="_blank" href="{{\Tools::url_pdf($subasta->cod_sub,$subasta->reference,'adj')}}">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</a> <br>
                        </p> 
                    @endif
                    @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                        <p class="text-center" style="background-color:#9e190a;padding: 20px 0; ">
                            <a  style="color:#FFFFFF"   href="{{ $url_tiempo_real }}" title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                        </p>
                    @endif  
                </div>
            </div>
        @endforeach
    </div>
</div>

@else
<?php 
    $auction = head($data['auction_list']);

    $subasta        = new App\Models\Subasta;  
    $subasta->cod   =  $auction->cod_sub;
    $auction = $subasta->getInfSubasta();
    

$file_code = $auction->emp_sub . '_' . $auction->cod_sub . '_' .$auction->reference;




?>
<section class="section-especial">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 titulo-subasta">
                <div class="col-xs-12 col-sm-6 np">
                    <div class="img-content-auction">
                        <h2 style="font-weight: 900;margin-top: 0;">{{$auction->des_sub}}</h2>
                    </div>
                    @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                        <p 
                            class="text-center" 
                            style="background-color: #9e190a;padding: 10px 0;width: 130px;"
                        >
                            <a 
                                style="color:#FFFFFF"   
                                href="{{ $url_tiempo_real }}" 
                                title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" 
                                target="_blank">
                                    {{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}
                            </a>
                        </p>
                    @endif  

                </div>
            
                <div class="col-xs-12 col-sm-6 col-md-6 np">
                    <div class="col-xs-6 col-sm-7 exposition-auction">
                        <div class="title-exposition">
                            <h4><strong>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_exposicion') }}</strong></h4>
                            @if(!empty($auction->expofechas_sub))
                                <p><?= $auction->expofechas_sub ?></p>
                            @endif
                            @if(!empty($auction->expohorario_sub))
                                <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $auction->expohorario_sub ?> </p>
                            @endif
                            @if(!empty($auction->expolocal_sub))
                                <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}:  <?= $auction->expolocal_sub ?></p>
                            @endif
                            @if(!empty($auction->expomaps_sub))
                                <div class="google-howGet">
                                    <a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $auction->expomaps_sub ?>">
                                        {{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-5">
                        <div class="title-exposition">
                        <h4><strong>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_subasta') }}</strong></h4>
                        @if(!empty($auction->sesfechas_sub))
                           <p> <?= $auction->sesfechas_sub ?></p>
                        @endif
                        @if(!empty($auction->seshorario_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $auction->seshorario_sub ?> </p>
                        @endif
                        @if(!empty($auction->seslocal_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $auction->seslocal_sub ?></p>
                        @endif
                        @if(!empty($auction->sesmaps_sub))
                            <div class="google-howGet">
                                <a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $auction->sesmaps_sub ?>">
                                    {{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}
                                </a>
                            </div>
                        @endif   
                        </div>
                    </div>

                       </div> 
            
                <div class="col-xs-12 np text-right">
                    <div class="cat-inline">
                        @if($subasta->upcatalogo == 'S' && file_exists("files/". $file_code ."_cat.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_cat.pdf" role="button">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</a></div>                 
                        @endif
                        @if( $subasta->uppdfadjudicacion == 'S' && file_exists("files/". $file_code ."_man.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_man.pdf"  role="button">{{ trans(\Config::get('app.theme').'-app.subastas.manual') }}</a></div>
                        @endif
                        @if( $subasta->uppdfadjudicacion == 'S' && file_exists("files/". $file_code ."_adj.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_man.pdf"  role="button">{{ trans(\Config::get('app.theme').'-app.subastas.adj') }}</a></div>
                        @endif
                        @if( $subasta->uppreciorealizado == 'S' && file_exists("files/". $file_code ."_pre.pdf"))
                            <div><a target="_blank" href="/files/<?=$file_code?>_pre.pdf"  class="cat-pdf-single price-done" role="button">{{ trans(\Config::get('app.theme').'-app.subastas.pre') }}</a></div>
                        @endif
                    </div>
                </div>
            </div>
            
            
            
            
            
            <div class="col-xs-12 col-sm-12 np" style="margin-top: 20px;">
        @foreach($data['auction_list'] as $auc) 
        
    
        <?php

            $url_lotes=\Routing::translateSeo('subasta').$auc->cod_sub."-".str_slug($auc->name)."-".$auc->id_auc_sessions;  
            if($finished == 'true' && $auc->tipo_sub == 'W' && ($auc->subc_sub == 'S' ||   $auc->subc_sub == 'A') && strtotime($auc->session_start) < strtotime("now") ){

                $url_lotes.="?order=fbuy";
            }else{
                 $url_lotes.="?order=ref";
            }
            
            $url_tiempo_real=\Routing::translateSeo('api/subasta').$auc->cod_sub."-".str_slug($auc->name)."-".$auc->id_auc_sessions;

         ?>
        <div class="col-xs-12 titulo-sesion"><h3>{{ $auc->name}}</h3></div>
            <div class="col-xs-12 col-sm-2 col-md-2 image-info-subasta">
                <div class="img-content-auction">
                    <a href=<?= $url_lotes ?>>
                        <img style="margin-top: 10px;"                                     
                            src="/img/load/subasta_large/SESSION_{{ $auc->file_code}}.jpg" 
                            alt="{{ $auc->name}}" 
                            class="img-responsive" 
                            style="margin: 0 auto;"
                            />
                    </a>

                </div>
                <div class="botones-subastas">
                    <a title="{{ $auction->name }}" href="{{ $url_lotes }}" style="border-radius: 0;margin-top: 5px; width: 100%;" class=" btn btn-lotes btn-color">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                    @if( $auc->tipo_sub =='W' &&   strtotime($auc->session_end) > time() )
                        <a  style="border-radius: 0;margin-top: 10px; width: 100%;" class=" btn btn-lotes btn-color pujar-envivo" href="{{ $url_tiempo_real }}" title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$auc->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$auc->session_end),'d/m/Y H:i') }}" target="_blank">Puja en vivo</a>
                    @endif  
                </div>
                
            </div>

                
                <?php 
                //este código devuelve el listado de ordenacion de catalogo, pero no detecta si hay lotes
                /*
                     $secModel        = new App\Models\Sec;
                     //le pasamos el codigo de la subasta
                     $sections = $secModel->getOrtsecByAuction($subasta->cod);
                 
                 */
                //necesitamos hacerlo así para saber que hay lotes activos
                    $subasta = new App\Models\Subasta();
                    $subasta->select_filter = " ORTSEC0.LIN_ORTSEC0,NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0) DES_ORTSEC0   ";                    
                    
                    //$subasta->select_filter = "SEC.COD_SEC, COUNT(COD_SEC)";
                    $subasta->join_filter = "JOIN FXSEC SEC ON (SEC.COD_SEC = HCES1.SEC_HCES1 ) ";
                    $subasta->join_filter .= "JOIN FGORTSEC1 ORTSEC1 ON (ORTSEC1.SEC_ORTSEC1 = SEC.COD_SEC  AND ORTSEC1.EMP_ORTSEC1 = HCES1.EMP_HCES1 ) ";
                    $subasta->join_filter .= "JOIN FGORTSEC0 ORTSEC0 ON (ORTSEC0.sub_ORTSEC0 =ORTSEC1.sub_ORTSEC1 AND ORTSEC0.EMP_ORTSEC0 = HCES1.EMP_HCES1  and ORTSEC0.LIN_ORTSEC0 =ORTSEC1.LIN_ORTSEC1 ) ";
                    $subasta->join_filter .= "LEFT JOIN FGORTSEC0_LANG ORTSEC0_LANG ON (ORTSEC0_LANG.sub_ORTSEC0_LANG = ORTSEC1.sub_ORTSEC1 AND ORTSEC0_LANG.EMP_ORTSEC0_LANG = HCES1.EMP_HCES1  and ORTSEC0_LANG.LIN_ORTSEC0_LANG =ORTSEC1.LIN_ORTSEC1  AND ORTSEC0_LANG.LANG_ORTSEC0_LANG = '". Config::get('app.language_complete')[Config::get('app.locale')]   . "')" ;
                    $subasta->where_filter = "  AND SEC.BAJAT_SEC = 'N' AND SEC.GEMP_SEC = '". Config::get('app.gemp')."' ";
                    $subasta->group_by = "ORTSEC0.LIN_ORTSEC0, NVL(ORTSEC0_LANG.DES_ORTSEC0_LANG,  ORTSEC0.DES_ORTSEC0), ORTSEC0.ORDEN_ORTSEC0";
                    $subasta->order_by_values = "ORTSEC0.ORDEN_ORTSEC0";
                    
                    $subasta->where_filter .= " AND ORTSEC1.SUB_ORTSEC1 = ASIGL0.SUB_ASIGL0 AND \"id_auc_sessions\" =  ".  $auc->id_auc_sessions ;
                    $sections= $subasta->getLots("small");
                
                

                ?>
                                
            
         

            <div class="sesion-categories col-xs-12 col-sm-10">
                @if(count($sections) > 0)
                <div class="col-sm-12">
                    <div class="sesion-title">
                        <h3><strong>{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</strong></h3>
                    </div>    
                </div>
                @endif
                    @foreach($sections as $section)
                        <div class="sesion-cat-wrapper col-xs-6 col-sm-4 col-md-3 text-center">
                            <div class="sesion-cat-content" role="button">
                                <a href="{{$url_lotes}}&lin_ortsec={{$section->lin_ortsec0}}">    
                                    <div class="cat_lot_content">
                                        <img style="max-height: 100%; margin: 0 auto;"src="{{Config::get('app.url').'/img/load/subasta_medium/ORTSEC_'.Config::get('app.emp').'_'.$auc->cod_sub.'_'.$section->lin_ortsec0.'.jpg'}}" class="img-responsive" />                                    
                                    </div>
                                </a>
                            </div>
                            <h5 class="text-center"><strong>{{$section->des_ortsec0}}</strong></h5>
                        </div>
                    @endforeach
                </div>
<div class="divider col-xs-12"></div>
            @endforeach
        </div>
        </div>
    </div>
</section>





@endif
