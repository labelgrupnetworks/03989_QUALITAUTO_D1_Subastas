<?php

namespace App\Providers;

use App\Services\Content\ManagementService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(ManagementService::class, function () {
			return new ManagementService();
		});
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

		Auth::provider('md5', function ($app, array $config) {
			return new Md5UserProvider($config['model']);
		});

		LogViewer::auth(function ($request) {
			return Session::has('user.admin') && in_array(mb_strtolower(Session::get('user.usrw')), ["subastas@labelgrup.com"]);
		});
	}
}
