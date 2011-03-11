<?php
/**
 * This file is part of chuggle.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace paindriven\chuggle;

/**
 * ChangesService class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class ChangesService
{
	/**
	 * @var Logger the logger
	 */
	private $logger;
	/**
	 * @var string the target dir
	 */
	private $targetDir;
	/**
	 * @var string the repository base
	 */
	private $repoDir;

	/**
	 * @var array the file-class map
	 */
	private $map;

	/**
	 * Constructor
	 *
	 * @param Logger $logger    the logger
	 * @param string $targetDir the target directory
	 * @param string $repoDir   the repository bsae
	 * @param array  $map       the file-class map
	 */
	function __construct(Logger $logger, $targetDir, $repoDir, $map)
	{
		$this->logger    = $logger;
		$this->targetDir = $targetDir;
		$this->repoDir   = $repoDir;
		$this->map       = $map;
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
		$this->logger->log('calculating changes...');

		$this->logger->log(' -> collect changes...');
		$changes = $this->retrieveGitChanges();

		$this->logger->log(' -> process changes...');
		foreach ($changes as $change) {
			if (false === strpos($change, $this->targetDir))
				continue;
			list($added, $removed, $file) = preg_split('/\\t/', $change);
			if (isset($this->map[$file]))
				$result->changes($this->map[$file], $added+$removed);
		}

		return $result;
	}

	private function retrieveGitChanges()
	{
		$cwd = getcwd();
		chdir($this->repoDir);
		$out = `git log --all -M -C --numstat --format="%n"`;
		chdir($cwd);

		return preg_split('/\\n/', $out);
	}
}

?>