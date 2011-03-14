<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence;

/**
 * ChangesService class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class ChangesService
{
	/**
	 * @var object the scm service
	 */
	private $scm;

	/**
	 * @var string the target dir
	 */
	private $targetDir;

	/**
	 * @var string the repository base
	 */
	private $repoDir;

	/**
	 * Constructor
	 *
	 * @param object $scm       the scm service
	 * @param string $targetDir the target directory
	 * @param string $repoDir   the repository bsae
	 */
	function __construct($scm, $targetDir, $repoDir)
	{
		$this->scm       = $scm;
		$this->targetDir = $targetDir;
		$this->repoDir   = $repoDir;
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
		$changes = $this->scm->changes($this->repoDir);

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