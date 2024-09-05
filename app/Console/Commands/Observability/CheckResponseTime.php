<?php

namespace App\Console\Commands\Observability;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

class CheckResponseTime extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'check:response-time';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check the response time of multiple URLs';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$urls = [
			// Urls a comprobar
		];

		$client = new Client();
		$promises = [];

		foreach ($urls as $url) {
			$start = microtime(true);

			// Lanza una solicitud asíncrona
			$promises[$url] = $client->getAsync($url)->then(
				function ($response) use ($url, $start) {
					$end = microtime(true);
					$duration = $end - $start;
					$statusCode = $response->getStatusCode();
					$this->info("URL: $url - Status: $statusCode - Time: {$duration}s");
				},
				function ($exception) use ($url) {
					$this->error("Failed to connect to $url: " . $exception->getMessage());
				}
			);
		}

		// Espera a que todas las solicitudes terminen
		Utils::settle($promises)->wait();

		return 0;
	}
}
