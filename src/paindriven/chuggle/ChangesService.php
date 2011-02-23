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

		$this->logger->log('running git...');
		$out = $this->retrieveGitChanges($this->targetDir, $this->repoDir);

		$this->logger->log('splitting changes...');
		foreach ($out as $raw) {
			list($noc, $file) = explode(',', $raw);
			if (!isset($this->map[$file])) {
				continue;
			}
			$result->changes($this->map[$file], (int) $noc);
		}

		return $result;
	}

	private function retrieveGitChanges()
	{
		$cwd = getcwd();
		chdir($this->targetDir);

		$gitCmd = sprintf(
				'git log --all -M -C --name-only | grep "^%s.*\.php" | sort | uniq -c | sort | awk \'BEGIN {print "count,file"} {print $1 "," $2}\'',
				ltrim(str_replace($this->repoDir, '', $this->targetDir), '/'), $this->targetDir
		);
		exec($gitCmd, $out);
		chdir($cwd);

		return $out;
	}
}

?>