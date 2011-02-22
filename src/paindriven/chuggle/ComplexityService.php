<?php
/**
 * This file is part of chuggle.php
 *
 * Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace paindriven\chuggle;

/**
 * ComplexityService class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class ComplexityService
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
	 * Constructor
	 *
	 * @param Logger $logger    the logger
	 * @param string $targetDir the target directory
	 * @param string $repoDir   the repository bsae
	 */
	public function __construct(Logger $logger, $targetDir, $repoDir)
	{
		$this->logger    = $logger;
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
	public function calculate(Collector $result)
	{
		$this->logger->log('calculating complexity...');

		$this->logger->log('running PDepend...');
		$this->runPdepend();
		$this->logger->log('gathering file-class maps...');
		$this->gatherFileClassMap();

		$this->logger->log('gathering complexities...');
		$logXml = simplexml_load_file($this->logFile);
		foreach ($logXml->package->class as $class) {
			$nom = (int) $class['nom'];
			$wmc = (int) $class['wmc'];
			$ac = $nom ? $wmc / $nom : 0;
			$class = (string) $class['name'];

			$result->complexity($class, $ac);
		}

		return $result;
	}

	private function runPdepend()
	{
		$this->logFile = get_temp_dir($this->targetDir) . '/pdepend.log';

		exec(sprintf('pdepend --summary-xml=%s %s', $this->logFile, $this->targetDir));
	}

	private function gatherFileClassMap()
	{
		$logXml = simplexml_load_file($this->logFile);
		$this->map = array();
		foreach ($logXml->package->class as $class) {
			$file = str_replace($this->repoDir, '', $class->file['name']);
			$class = (string) $class['name'];

			$this->map[$file] = $class;
		}
	}

	/**
	 * provides file-class map
	 *
	 * @return array
	 */
	public function map()
	{
		return $this->map;
	}
}

?>