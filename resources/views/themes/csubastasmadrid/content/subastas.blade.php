<div class="container">
	<div class="row">
		<div class="col-xs-12">
			@if (request('finished') == 'true')
				<h1 class="titlePage"> {{ trans(\Config::get('app.theme') . '-app.subastas.price_made_long') }}</h1>
			@else
				<h1 class="titlePage"> {{ $data['name'] }}</h1>
			@endif
		</div>
		<?php
		if (!empty($_GET['finished'])) {
		    foreach ($data['auction_list'] as $key => $sub_finished) {
		        if (strtotime($sub_finished->session_end) <= time() && $_GET['finished'] == 'false') {
		            unset($data['auction_list'][$key]);
		        } elseif (strtotime($sub_finished->session_end) > time() && $_GET['finished'] == 'true') {
		            unset($data['auction_list'][$key]);
		            krsort($data['auction_list']);
		        }
		    }
		}
		?>

		@if ($data['subc_sub'] != 'H')

			@foreach ($data['auction_list'] as $subasta)
				<?php
				$url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
				$url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
				$url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);

				$url_lotes_no_vendidos = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions . '?no_award=1';

				// Se obtiene la descripción de la subasta
				$inf_subasta = new \App\Models\Subasta();
				$inf_subasta->cod = $subasta->cod_sub;
				$inf_subasta->id_auc_sessions = $subasta->id_auc_sessions;
				$ficha_subasta = $inf_subasta->getInfSubasta();

				?>

				@include('front::includes.subasta', ['ficha_subasta' => $ficha_subasta, 'url_lotes' => $url_lotes, 'url_tiempo_real' => $url_tiempo_real, 'url_subasta' => $url_subasta, 'url_lotes_no_vendidos' => $url_lotes_no_vendidos])
			@endforeach
		@elseif(Session::has('user'))
			<?php
			$historico = [];
			foreach ($data['auction_list'] as $value) {
			    $year = date('Y', strtotime($value->session_start));

			    $historico[$year][$value->cod_sub][] = $value;
			    usort($historico[$year][$value->cod_sub], function ($a, $b) {
			        return strcmp($a->reference, $b->reference);
			    });
			}

			?>
			@foreach ($historico as $key => $sub)
				<div class="col-xs-12 sub-h">
					<div class="dat">
						{{ $key }}
					</div>
				</div>
				@foreach ($sub as $sessions)
					@foreach ($sessions as $subasta)
						<?php
						$url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
						$url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
						$url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);

						// Se obtiene la descripción de la subasta
						$inf_subasta = new \App\Models\Subasta();
						$inf_subasta->cod = $subasta->cod_sub;
						$inf_subasta->id_auc_sessions = $subasta->id_auc_sessions;
						$ficha_subasta = $inf_subasta->getInfSubasta();

						?>


						@include('front::includes.subasta', ['ficha_subasta' => $ficha_subasta, 'url_lotes' => $url_lotes, 'url_tiempo_real' => $url_tiempo_real, 'url_subasta' => $url_subasta])

					@endforeach
				@endforeach
			@endforeach
		@else
			<div class=" col-lg-12">
				<h1 class="tit text-center"> {{ trans(\Config::get('app.theme') . '-app.subastas.not-register') }}</h1>
			</div>
		@endif
	</div>
</div>
