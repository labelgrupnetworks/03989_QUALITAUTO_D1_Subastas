<?php

namespace App\Services\Content;

use App\libs\CacheLib;
use App\Models\V5\Web_Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlockService
{
	public function getResultBlockByKeyname($keyname, $replace = array())
	{
		try {
			$res = $this->getBlockByKeyname($keyname);
			$key_cache = $keyname;

			if(empty($res)) {
				return null;
			}

			if(empty($res->products)) {
				return null;
			}

			$sql = $res->products;

			if($this->sqldanger($sql)) {
				return null;
			}

			$cache = $res->time_cache;

			foreach ($replace as $key => $value) {
				$sql = str_replace("[" . $key . "]", $value, $sql);
				$key_cache .= $key . "_" . $value;
			}

			return (!empty($cache) && $cache > 0)
				? CacheLib::useCache($key_cache, $sql)
				: DB::select($sql);

		} catch (\Exception $e) {
			Log::emergency('Error blockByKeyname: $keyname' . $e);
			return NULL;
		}
	}

	private function getBlockByKeyname($keyname)
	{
		return Web_Block::query()
			->where('key_name', $keyname)
			->where('enabled', 1)
			->first();
	}

	private function sqldanger($sql)
	{
		$dangerous =  array('delete', 'insert', 'created', 'drop', 'alter', 'update');
		$sql = strtolower($sql);

		foreach ($dangerous as $danger) {
			if (stripos($sql, $danger) !== false) {
				return true;
			}
		}
		return false;
	}
}
