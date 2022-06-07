
{{-- las etiquetas van a parta para simplificar el código --}}
<?php
	#lo pongo aquí para que funcione tambie en lotes relacionados y destacados.
	if($subasta_venta){
		$precio_salida = \Tools::moneyFormat($item->impsalhces_asigl0,"",2);
	}

?>
<div class="{{$class_square}}  square" {!! $codeScrollBack !!}>
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >




        <div class="item_lot_private">
            <div class="item_img">
                <div data-loader="loaderDetacados" ></div>
                <img class="img-responsive"  src="{{$img}}" alt="{{$titulo}}">
            </div>

            <div class="data-container">
					<div class="description_lot">
						<?= $titulo?>
					</div>

            </div>

        </div>
    </a>
</div>
