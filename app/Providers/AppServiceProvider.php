<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Paginator::useBootstrap();

		if (env('FORCE_HTTPS')) {
			URL::forceScheme('https');
		}

		if (!Collection::hasMacro('paginate')) {

			Collection::macro(
				'paginate',
				function ($perPage = 12, $page = null, $options = []) {
					$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
					return (new LengthAwarePaginator(
						$this->forPage($page, $perPage),
						$this->count(),
						$perPage,
						$page,
						$options
					))
						->withPath('');
				}
			);
		}
	}
}
