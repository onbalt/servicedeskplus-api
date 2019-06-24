<?php

namespace Onbalt\ServicedeskplusApi;

use Illuminate\Support\ServiceProvider;

class ServicedeskplusApiServiceProvider extends ServiceProvider {

	/**
	 * Publishes configuration file.
	 * #php artisan vendor:publish -tag=manageengine-config
	 *
	 * @return void
	 */
	public function boot() {
		if ($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/servicedeskplus-api.php' => config_path('servicedeskplus-api.php'),
					], 'servicedeskplus-api-config');
		}
	}

	/**
	 * Make config publishment optional by merge the config from the package.
	 *
	 * @return void
	 */
	public function register() {
		$this->mergeConfigFrom(
				__DIR__ . '/../config/servicedeskplus-api.php', 'servicedeskplus-api'
		);
	}

}
