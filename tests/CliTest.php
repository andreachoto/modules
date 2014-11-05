<?php namespace Tests;

class CliTest extends TestCase {

	public function setUp()
	{
		parent::setUp();

		$this->artisan = $this->app['artisan'];
	}

	public function call($command, $options = [])
	{
		$this->artisan->call($command, $options);
	}

	public function testGenerators()
	{
		$this->cleanup();
		
		$commandOptions = [
			'module:setup' => [],
			'module:make' => ['name' => 'Bar'],
			'module:model' => ['model' => 'Bazz', 'module' => 'Bar'],
			'module:use' => ['module' => 'Bar'],
 			'module:controller' => ['controller' => 'FooController'],
 			'module:command' => ['name' => 'FooCommand'],
 			'module:disable' => ['module' => 'Bar'],
 			'module:enable' => ['module' => 'Bar'],
 			'module:provider' => ['name' => 'ConsoleServiceProvider'],
 			'module:route-provider' => ['module' => 'Bar'],
		];

		foreach ($commandOptions as $command => $options)
		{
			$this->call($command, $options);
		}

		$this->cleanup();
	}

	public function cleanup()
	{
		$module = $this->app['modules']->get('Bar');

		if($module)
		{
			$module->delete();

			rmdir($module->getPath());
		}
	}

}