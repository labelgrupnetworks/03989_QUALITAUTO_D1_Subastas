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
