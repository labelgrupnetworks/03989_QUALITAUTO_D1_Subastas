<?php

namespace App\Console\Commands\Scripts;

use App\Models\V5\Web_Config;
use Illuminate\Console\Command;


class RemoveKeysInWebConfig extends Command
{
	protected $signature = 'label:remove-webconfig-keys';
	protected $description = 'Eliminar claves en desuso de la tabla web_config';

	private function keysToDelete()
	{
		return [
			'global_auctions_var',
			'twitter_user',
			'multiple_direcciones',
			'email_admin_association_user',
			'email_admin_new_user',
			'email_cedente_lot_comprado',
			'enable_email_buy',
			'email_change_info_client',
			'enable_email_order',
			'menu_admin',
			'linkedin_app_id',
			'default_category_es',
			'enable_email_overorder - DEPRECATED',
			'enable_email_overorder',
			'universalpay_3D',
			'universalpay_return',
			'universalpay_cancel',
			'enable_email_overbid',
			'enable_email_bid',
			'ini_licit',
			'redirect_erroruser',
			'home_enable_historic',
			'home_enable_featured_lots',
			'home_enable_big_buttons',
			'home_enable_services',
			'tr_show_aslot',
			'tr_show_info',
			'tr_show_video',
			'enable_direct_sale_auctions',
			'delivereaUrl',
			'icon_multiple_images',
			'enable_interest_tsec',
			'universalpay_code',
			'universalpay_key',
			'universalpay_environment',
			'universalpay_lang',
			'universalpay_moneda',
			'universalpay_token',
			'linkTiempoRealHome',
			'mosaic_blog_category',
			'test-admin',
			'sub-ortsec-departament',
			'assessment_registered',
			'auction_in_categories',
			'enable_general_auctions',
			'enable_historic_auctions',
			'enable_tr_auctions',
			'fecha_noindex_follow',
			'hide_sold_lot',
			'hide_sold_lots_V',
			'keywords_search',
			'search_lots_cerrados',
			'is_concursal',
			'default_lang',
			'queueEmails',
			'use_new_email_config',
			'fb_app_id',
			'email-buy-lot-not-selled',
			'admin_email_venta_articulo',
			'orderArticlesToSection',
			'exportcli_profesion',
			'PasswordWebService',
			'UserWebService',
			'DomainWebService',
			'almacenDevuelto',
			'substrRefBis',
			'codRecaptchaValoracion',
			'NIFvalidator',
			'custom_path_video',
			'routes_SEO',
			'email_comprador',
			'email_pagado_logistica',
			'email_double_opt_in',
			'email_double_opt_in_recaptcha',
			'enable_email_online_bid'

		];
	}

	public function handle()
	{
		$number = Web_Config::query()
			->withoutGlobalScopes()
			->whereIn('key', $this->keysToDelete())
			->delete();

		$this->info("Claves eliminadas correctamente de web_config: {$number}.");
		return 0;
	}
}
