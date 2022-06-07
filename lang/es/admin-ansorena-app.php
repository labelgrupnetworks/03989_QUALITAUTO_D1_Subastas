<?php
#galería
if(\Config::get("app.emp") == '003' || \Config::get("app.emp") == '004'){
	return [

		'title' => array(
			'auctions'=>'Exposiciones',
			'auction'=>'Exposición',
			'lot' => 'Obra',
			'lots' => 'Obras',
			'reference_lot' => 'Referencia Obra',
			'blog' => 'Noticias'
		),
		'fields' => array(
			'lot' => array(
				'sub_asigl0' => 'Id Exposicion',
				'desc_hces1' => 'Título Obra',

			),
			'cod_sub' => 'Código exposición',
			'des_sub' => 'Nombre exposición',
			'subc_sub' => 'Estado exposición',
			'tipo_sub' => 'Tipo exposición',
			'tipo_sub_e' => 'Exposición',
			'subabierta_sub' => 'Exposición abierta',
			'compraweb_sub' => 'Fondo de galería',
			'buyoption' => 'Fondo de galería',
			'opcioncar_sub' => 'Exposición Online',
			'newsletter2' => 'Newsletter Joyería',
			'newsletter3' => 'Newsletter Subastas Pintura',
			'newsletter4' => 'Newsletter Subastas Muebles y artes decorativas',
			'newsletter5' => 'Newsletter Subastas Joyas',
			'newsletter6' => 'Newsletter Galería',
			'newsletter7' => 'Newsletter Condecoraciones',
			'newsletter20' => 'Suscripción catálogo galería',
			'nllist2_cliweb' => 'Newsletter Joyería',
			'nllist3_cliweb' => 'Newsletter Subastas Pintura',
			'nllist4_cliweb' => 'Newsletter Subastas Muebles y artes decorativas',
			'nllist5_cliweb' => 'Newsletter Subastas Joyas',
			'nllist6_cliweb' => 'Newsletter Galería',
			'nllist7_cliweb' => 'Newsletter Condecoraciones',
			'enviocatalogo' => 'Suscripción catálogo subastas',
			"extrainfo" => "Edición",
			'envcat_cli2' => 'Cat. subastas',
			'impsalhces_asigl0' => 'Precio',
			'startprice' => 'Precio',
		),
		'help_fields' => array(
			'buyoption' => 'Añadirá este lote al Fondo de galería',
			'compraweb_sub' => 'Fondo de galería',
			'opcioncar_sub' => 'Marcará la exposición como Online',
		)


	];
#Web normal
}else{
	return[];
}
