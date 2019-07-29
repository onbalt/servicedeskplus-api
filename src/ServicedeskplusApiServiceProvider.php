<?php

namespace Onbalt\ServicedeskplusApi;

use Illuminate\Support\ServiceProvider;

class ServicedeskplusApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Publishes configuration file.
	 * #php artisan vendor:publish --tag=servicedeskplus-api-config
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
        $this->app->singleton('servicedeskplus-api', function ($app) {
            return new ServicedeskplusApi($app['config']->get('servicedeskplus-api'));
        });
        $this->app->alias('servicedeskplus-api', ServicedeskplusApi::class);
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ServicedeskplusApi::class, 'servicedeskplus-api'];
    }

}
