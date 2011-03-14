<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence;

/**
 * Collector class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Collector
{
	/**
	 * @var array the result holder
	 */
	private $result = array();

	/**
	 * @var array filename - classname map
	 */
	private $map = array();

	/**
	 * adds complexity to given class
	 *
	 * @param string $class the class name
	 * @param float  $ac    the average complexity
	 *
	 * @return void
	 */
	public function averageMethodComplexity($class, $ac)
	{
		$this->prepareClass($class);
		$this->result[$class][1] = $ac;
	}

	public function lagestMethodComplexity($class, $complexity)
	{
		$this->prepareClass($class);
		$this->result[$class][2] = max($this->result[$class][2], $complexity);
	}

	/**
	 * adds complexity to given class
	 *
	 * @param string $fileName the file name
	 * @param int    $noc      number of changes
	 *
	 * @return void
	 */
	public function changes($fileName, $noc)
	{
		if (!isset($this->map[$fileName]))
			return;

		$className = $this->map[$fileName];
		$this->prepareClass($className);
		$this->result[$className][0] += $noc;
	}

	private function prepareClass($class)
	{
		if (!isset($this->result[$class])) {
			$this->result[$class] = array(0, 0, 0);
		}
	}

	public function classMap($fileName, $className)
	{
		$this->map[$fileName] = $className;
	}

	/**
	 * dumps the collected data to given file as json string
	 *
	 * @param string $file the output file
	 *
	 * @return void
	 */
	public function dumpJson($file)
	{
		file_put_contents($file, json_encode($this->result));
	}
}

?>