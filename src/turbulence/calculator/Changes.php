<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence\calculator;

use turbulence\Collector;

/**
 * Changes class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Changes
{
	/**
	 * @var Git the scm service
	 */
	private $scm;

	/**
	 * @var string the target dir
	 */
	private $targetDir;

	/**
	 * Constructor
	 *
	 * @param Git    $scm       the scm service
	 * @param string $targetDir the target directory
	 */
	function __construct($scm, $targetDir)
	{
		$this->scm       = $scm;
		$this->targetDir = $targetDir;
	}

	/**
	 * calculate
	 *
	 * @param Collector $result
	 *
	 * @return Collector
	 */
	function calculate(Collector $result)
	{
		$changes = $this->scm->changes();

		foreach ($changes as $change) {
			if (false === strpos($change, $this->targetDir))
				continue;
			list($added, $removed, $fileName) = preg_split('/\\t/', $change);
			$result->changes($fileName, $added+$removed);
		}

		return $result;
	}
}

?>