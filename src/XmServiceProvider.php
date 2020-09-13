<?php

namespace E4nar\Xm;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class XmServiceProvider extends ServiceProvider {
	
	public function register() {
		
		$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'xmconfig');
		
		// whenever a class needs an object that implements CompanyInfoApi, a DatahubNasdaq object will be served
		$this->app->bind('E4nar\Xm\Nasdaq\CompanyInfoApi', 'E4nar\Xm\Utilities\DatahubNasdaq');
		
		// whenever a class needs an object that implements StockInfoApi, a RapidStock object will be served
		$this->app->bind('E4nar\Xm\Contracts\Nasdaq\StockInfoApi', 'E4nar\Xm\Utilities\RapidStock');
		
	}
	
	public function boot() {
		
		if ($this->app->runningInConsole()) {
			
			$this->publishes([
				__DIR__.'/../config/config.php' => config_path('xmconfig.php'),
			], 'config');
			
			
			$this->publishes([
				__DIR__.'/../resources/js' => public_path('xm/js'),
				__DIR__.'/../resources/sass' => public_path('xm/sass'),
			], 'assets');
			
		}
		
		$this->registerRoutes();
		
		$this->loadViewsFrom(__DIR__.'/../resources/views', 'xm');
		

		
		
		
		// applying default string length for keys in database
		Schema::defaultStringLength(191);
		
	}
	
	protected function registerRoutes() {
			
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
		
		$this->loadRoutesFrom(__DIR__.'/../routes/api.php');
	
	}
	
	protected function routeConfiguration() {
		
		return [
			'prefix'     => config('xmconfig.route_prefix'),
			'middleware' => config('xmconfig.route_middleware'),
		];
		
	}
	
}
