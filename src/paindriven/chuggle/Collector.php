<?php
/**
 * This file is part of chuggle.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace paindriven\chuggle;

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
	 * adds complexity to given class
	 *
	 * @param string $class the class name
	 * @param float  $ac    the average complexity
	 *
	 * @return void
	 */
	public function complexity($class, $ac)
	{
		$this->addToResult($class, 1, $ac);
	}

	/**
	 * adds complexity to given class
	 *
	 * @param string $class the class name
	 * @param int    $noc   number of changes
	 *
	 * @return void
	 */
	public function changes($class, $noc)
	{
		$this->addToResult($class, 0, $noc);
	}

	private function addToResult($class, $pos, $value)
	{
		if (!isset($this->result[$class])) {
			$this->result[$class] = array(0, 0);
		}
		$this->result[$class][$pos] += $value;
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