<?php
$subastaObj        = new \App\Models\Subasta();
$has_subasta = $subastaObj->auctionList ('S', 'O');


if(!empty($has_subasta) && count($has_subasta)== 1){

		 $subasta = head($has_subasta);
		 $url_web = \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions, $subasta->reference);
}else{

	 $url_web = \Routing::translateSeo('subastas-online');
}
 ?>

<script>
window.location.href = '{{$url_web}}';
</script>











































</div>

















































<script>
	<?php
        $key = "lotes_destacados";

        $replace = array(
          'lang' => \Tools::getLanguageComplete(Config::get('app.locale')) ,'emp' => Config::get('app.emp') ,
        );
    ?>
    var replace = <?= json_encode($replace) ?>;
    var key ="<?= $key ?>";

    $( document ).ready(function() {
        ajax_carousel(key,replace);


     });








</script>
