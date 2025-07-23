<?php

namespace App\Support\Database;

use Closure;
use Illuminate\Support\Facades\DB;

class SessionOptions
{
	public static function enableLinguisticSearch(): void
	{
		DB::select("alter session set nls_comp=linguistic");
		DB::select("alter session set nls_sort=binary_ai");
	}

	public static function disableLinguisticSearch(): void
	{
		DB::select("alter session set nls_comp=binary");
		DB::select("alter session set nls_sort=binary_ai");
	}

	public static function withLinguistic(Closure $callback)
	{
		self::enableLinguisticSearch();
		try {
			return $callback();
		} finally {
			self::disableLinguisticSearch();
		}
	}
}
