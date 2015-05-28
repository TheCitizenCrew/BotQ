<?php

//class_alias('Illuminate\Support\Facades\Artisan', 'Artisan');
use Illuminate\Support\Facades\Artisan ;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
	public function setUp()
	{
		parent::setUp();
		echo __METHOD__, "\n";
		$this->artisanMigrateRefresh();
	}
	
	public function tearDown()
	{
		$this->artisanMigrateReset();
		parent::tearDown();  // Moving that call to the top of the function didn't work either.
	}
	
	public function artisanMigrateRefresh()
	{
		Artisan::call('migrate');
	}
	
	public function artisanMigrateReset()
	{
		Artisan::call('migrate:reset');
	}
	
	/**
	 * Creates the application.
	 *
	 * @return \Laravel\Lumen\Application
	 */
	public function createApplication()
	{
		echo __METHOD__, "\n";
		
		return require __DIR__ . '/../bootstrap/app.php';
	}

}
