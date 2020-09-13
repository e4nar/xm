<?php

namespace E4nar\Xm\Tests;

use E4nar\Xm\XmServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {
	
	public function setUp(): void {
		parent::setUp();
		// additional setup
		
	}
	
	protected function getPackageProviders($app) {
		return [
			XmServiceProvider::class,
		];
	}
	
	protected function getEnvironmentSetUp($app) {
		// perform environment setup
		
	}
	
}