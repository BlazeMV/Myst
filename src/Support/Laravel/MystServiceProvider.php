<?php

namespace Blaze\Myst\Support\Laravel;

use Blaze\Myst\BotsManager;
use Illuminate\Support\ServiceProvider;

class MystServiceProvider extends ServiceProvider
{
	/**
	 * Boot the service provider.
	 */
	public function boot()
	{
		$this->makeConfig();
		
		$this->registerCommands();
	}
	
	protected function makeConfig()
	{
		$config_path = dirname(__DIR__) . '/config.php';
		$this->publishes([$config_path => config_path('myst.php')], 'Myst');
	}
	
	public function registerCommands(){
		if ($this->app->runningInConsole()) {
//			$this->commands([Controller::class]);
		}
	}
	
	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->app->singleton('Blaze\Myst\Bot', function () {
			$config = config('blazing');
			$manager = new BotsManager($config);
			return $manager->getActiveBot();
		});
	}
}