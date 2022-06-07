

@if (!empty($data["auction"]))
<div class="container">
	<div class="row it_ro">
            <div class="col-xs-12">
                <div class="col-sm-7 col-xs-12">
                    <div class="img-subasta">
                        <div class="img-border-auction">

                            <img  width="100%" src="/img/load/subasta_large/SESSION_{{ $data["auction"]->emp_sub }}_{{ $data["auction"]->cod_sub }}_{{ $data["auction"]->reference }}.jpg" class="img-responsive">
                           
                        </div>
                        
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="col-xs-12">
                         <h1 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>
                         <p><?= empty($data["auction"]->descdet_sub)? ' ' : $data["auction"]->descdet_sub; ?></p>
                        
                    </div>
                    <div class="col-xs-12 desc-panel-aution">

                        
                        <div class="content-auction-desc">
                        <div class="block-auction exposition-des">
                            @if( (!empty($data["auction"]->expofechas_sub)) ||  (!empty($data["auction"]->expohorario_sub)) || (!empty($data["auction"]->expolocal_sub)) || (!empty($data["auction"]->expomaps_sub)))
                            <h4>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_exposicion') }}</h4>
                            @endif
                            @if(!empty($data["auction"]->expofechas_sub))
                                <p><?= $data["auction"]->expofechas_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->expohorario_sub))
                                <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->expohorario_sub ?> </p>
                            @endif
                            @if(!empty($data["auction"]->expolocal_sub))
                                <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->expolocal_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->expomaps_sub))
                                <p><a target="_blank" title="cómo llegar" href="https://maps.google.com/?q=<?= $data['auction']->expomaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
                            @endif
                        </div>
                        <div class="block-auction exposition-des">
                            @if((!empty($data["auction"]->sesfechas_sub)) || (!empty($data["auction"]->seshorario_sub)) || (!empty($data["auction"]->seslocal_sub)) || (!empty($data["auction"]->sesmaps_sub)))
                            <h4>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_subasta') }}</h4>
                            @endif
                            @if(!empty($data["auction"]->sesfechas_sub))
                               <p> <?= $data["auction"]->sesfechas_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->seshorario_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_horario') }}: <?= $data["auction"]->seshorario_sub ?> </p>
                            @endif
                            @if(!empty($data["auction"]->seslocal_sub))
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.inf_subasta_location') }}: <?= $data["auction"]->seslocal_sub ?></p>
                            @endif
                            @if(!empty($data["auction"]->sesmaps_sub))
                                <p><a target="_blank" title="{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}" href="https://maps.google.com/?q=<?= $data['auction']->sesmaps_sub ?>">{{ trans(\Config::get('app.theme').'-app.subastas.how_to_get') }}</a></p>
                            @endif
                        </div>
                        
                    </div>
                    
                        <div class="links-aution">
                            @foreach ($data['sessions'] as $session)
                            <div class="link-auction">
                                <p>{{$session->name}}</p><a></a><a class="btn btn-view-lot" title="Ver lotes" href="<?= Routing::translateSeo('subasta').$session->auction."-".str_slug($session->name."-".$session->id_auc_sessions) ?>">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                            </div>
                            @endforeach                           
                        </div>
                    </div>
                   <div class="col-xs-12 share-panel-auction">
                       <div>{{ trans(\Config::get('app.theme').'-app.subastas.shared_auctions') }}</div>
                       <ul>
                           
                           <li><a href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
                                <i class="fa fa-facebook"></i>
                               </a>
                           </li>
                           <li>
                               <a title="Compartir por e-mail" href="http://twitter.com/share?url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>&amp;text=<?= $data["auction"]->des_sub?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fa fa-twitter"></i></a>
                           </li>
                           <li>
                              <a title="Compartir por e-mail" href="mailto:?Subject={{ trans(\Config::get('app.theme').'-app.head.title_app') }}&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i class="fa fa-envelope"></i></a> 
                           </li>
                       </ul>
                   </div>
                    <?php

                    $file_code = $data["auction"]->emp_sub . '_' . $data["auction"]->cod_sub . '_' .$data["auction"]->reference;
        
                    ?>
                    
                     <div class="col-xs-12 np text-right">
                         <div class="cat-inline" style="margin-top: 10px;">
                        @if($data["auction"]->upcatalogo == 'S' && file_exists("files/". $file_code ."_cat.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_cat.pdf" role="button">{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</a></div>                 
                        @endif
                        @if( $data["auction"]->uppdfadjudicacion == 'S' && file_exists("files/". $file_code ."_man.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_man.pdf"  role="button">{{ trans(\Config::get('app.theme').'-app.subastas.manual') }}</a></div>
                        @endif
                        @if( $data["auction"]->uppdfadjudicacion == 'S' && file_exists("files/". $file_code ."_adj.pdf"))
                            <div><a target="_blank" class="cat-pdf-single" href="/files/<?=$file_code?>_man.pdf"  role="button">{{ trans(\Config::get('app.theme').'-app.subastas.adj') }}</a></div>
                        @endif
                        @if( $data["auction"]->uppreciorealizado == 'S' && file_exists("files/". $file_code ."_pre.pdf"))
                            <div><a target="_blank" href="/files/<?=$file_code?>_pre.pdf"  class="cat-pdf-single price-done" role="button">{{ trans(\Config::get('app.theme').'-app.subastas.pre') }}</a></div>
                        @endif
                    </div>
                </div>
                    
                </div>

            </div>
	</div>

</div>


@else
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-4">
			<div class="single_auction">
				<h1 class="titleSingle">{{trans(\Config::get('app.theme').'-app.lot.auction_not_found')}}</h1>
                        </div>
                </div>
        </div>
</div>

@endif
