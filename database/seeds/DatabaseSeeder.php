<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 * 
	 * Do not forget to run "composer dumpautoload" after add a new seeder class file.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('ChannelsAndMessagesSeeder');
	}

}

