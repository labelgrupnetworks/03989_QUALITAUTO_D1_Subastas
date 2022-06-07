<link href="{{ Tools::urlAssetsCache('/themes/'.$theme.'/style_subasta_large.css') }}" rel="stylesheet" type="text/css">
<div class="all-auctions color-letter">
	<div class="container">

		@if(!empty($data['auction_list']) && $data['auction_list'][0]->tipo_sub != 'V')

				<?php
					$finalized = [];
					$notFinalized = [];
				?>

				@foreach ($data['auction_list'] as $subasta)

					<?php
					//ver si la subasta está cerrada
					$SubastaTR = new \App\Models\SubastaTiempoReal();
					$SubastaTR->cod =$subasta->cod_sub;
					$SubastaTR->session_reference =  $subasta->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);
					$status  = $SubastaTR->getStatus();
					$subasta_finalizada = false;

					//obtener info de la subasta
					$subastaInfo = new \App\Models\Subasta();
					$subastaInfo->id_auc_sessions = $subasta->id_auc_sessions;
					$subastaInfo->cod =$subasta->cod_sub;
					$info = $subastaInfo->getInfSubasta();

					$subasta->descdet_sub = $info->descdet_sub;

					if(!empty($status) && $status[0]->estado == "ended"){
						$subasta_finalizada = true;
					}
					?>

					@if($subasta_finalizada)
						<?php array_push($finalized, $subasta); ?>
					@else
						<?php array_unshift($notFinalized, $subasta); ?>
					@endif

				@endforeach


				@if(count($notFinalized) > 0)

					<div class="row">
						@if(!(!empty($data['auction_list']) && count($data['auction_list']) > 0 && $data['auction_list'][0]->tipo_sub == 'V'))
							<div class="col-xs-12">
								<div class="auctions-list-title"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.next_auctions') }}</strong></div>
							</div>
						@endif

						@foreach ($notFinalized as $subasta)
							<div class="auctions-list col-xs-12">
								@include('content.subasta_largestyle')
							</div>
						@endforeach
					</div>


				@endif

				@if(count($finalized) > 0)
				<div class="row">
					<div class="col-xs-12">
						<div class="auctions-list-title"><strong>{{ trans(\Config::get('app.theme').'-app.subastas.finished_auctions') }}</strong></div>
					</div>

					@foreach ($finalized as $subasta)
					<div class="auctions-list col-xs-12">
						@include('content.subasta_largestyle')
					</div>
					@endforeach
				</div>

				@endif

		@endif
	</div>
</div>
