<?php

class ExampleTest extends TestCase
{

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		echo __METHOD__, "\n";

		$response = $this->call( 'GET', '/' );
		$this->assertResponseOk();
	}
}
