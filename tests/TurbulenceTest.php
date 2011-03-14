<?php

class TurbulenceTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$this->repoUrl = realpath(__DIR__.'/../');
		$this->path    = 'src';
		$this->output  = '/tmp/_turbulence_test';
	}

	protected function tearDown()
	{
		`rm -fr {$this->output}`;
	}

	/**
	 * @test
	 */
	public function createsAndFillOutputDir()
	{
		$this->runTurbulence();
		$this->assertTrue(is_dir($this->output), $this->out);
	}

	private function runTurbulence()
	{
		$cwd = getcwd();
		chdir(__DIR__.'/..');
		$this->out = `bin/turbulence -repo={$this->repoUrl} -path={$this->path} -out={$this->output}`;
		chdir($cwd);
	}

	/**
	 * @test
	 */
	public function outputIsAValidJson()
	{
		$this->runTurbulence();

		$jsonFile = $this->output.'/out.json';
		$this->assertTrue(is_file($jsonFile), $this->out);

		$json = file_get_contents($this->output.'/out.json');
		$jsonData = json_decode($json, true);
		$this->assertThat($jsonData, $this->isType('array'));
		$this->assertArrayHasKey('Changes', $jsonData);
		$this->assertArrayHasKey('Complexity', $jsonData);
		$this->assertArrayHasKey('Git', $jsonData);
		$this->assertArrayHasKey('Logger', $jsonData);
		$this->assertArrayHasKey('Collector', $jsonData);
	}
}

?>