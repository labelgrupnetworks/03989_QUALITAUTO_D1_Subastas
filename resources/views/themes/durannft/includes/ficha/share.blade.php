<?php
$titulo = $titulo ?? $data['subasta_info']->lote_actual->titulo_hces1;
?>
<div class="zone-share-social">
	<ul class="red ul-format d-flex align-items-center">

		<li>
		@if(Session::has('user') &&  !$retirado)

		<a class="d-flex align-items-center justify-content-center color-letter fav_element"
			data-display="static"
			href="javascript:">

			<i class="fa {{ $lote_actual->favorito ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"
				data-cod_sub="{{$lote_actual->cod_sub}}" data-cod_licit="0" data-ref="{{$lote_actual->ref_asigl0}}"
				data-action="{{ $lote_actual->favorito ? 'remove' : 'add' }}"
			></i>
		</a>

		@else
			<a class="d-flex align-items-center justify-content-center color-letter btn_login" data-display="static">
				<i class="fa fa-heart-o" aria-hidden="true"></i>
			</a>
		@endif
		</li>

		<li>
			<a class=" d-flex align-items-center justify-content-center color-letter share-item-control">
				<i class="fa fa-share-alt" aria-hidden="true"></i>
			</a>

		</li>

		<li class="share-item" style="display: none">
			<a class=" d-flex align-items-center justify-content-center color-letter"
				title="{{ trans($theme.'-app.lot.share_facebook') }}"
				target="_blank"
				href="http://www.facebook.com/sharer.php?u=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i
					class="fa fa-facebook"></i></a>
		</li>
		<li class="share-item" style="display: none">
			<a class=" d-flex align-items-center justify-content-center color-letter"
				title="{{ trans($theme.'-app.lot.share_twitter') }}"
				target="_blank"
				href="http://twitter.com/share?text=<?= $titulo.' '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>&url=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i
					class="fab fa-twitter"></i></a>
		</li>
		<li class="share-item" style="display: none">
			<a class=" d-flex align-items-center justify-content-center color-letter"
				target="_blank"
				title="{{ trans($theme.'-app.lot.share_email') }}"
				href="mailto:?Subject=<?= \Config::get('app.name')?>&body=<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"><i
					class="fas fa-envelope-open"></i></a>
		</li>
	</ul>

</div>


<script>
	$('.share-item-control').on('click', (event) => {

		const element = event.target;

		if(element.classList.contains('activate')){
			$('.share-item').hide('slow');
			element.classList.remove('activate');
			return;
		}

		$('.share-item').show('slow');
		element.classList.add('activate');
	})
</script>
