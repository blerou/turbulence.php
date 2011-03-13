<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence;

/**
 * ComplexityService class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class ComplexityService
{
	/**
	 * @var string the subject dir
	 */
	private $subjectDir;

	/**
	 * @var string the repository base
	 */
	private $repoDir;

	/**
	 * @var string the output dir
	 */
	private $outputDir;

	/**
	 * Constructor
	 *
	 * @param string $subjectDir the target directory
	 * @param string $repoDir    the repository bsae
	 * @param string $outputDir  the output dir
	 */
	public function __construct($subjectDir, $repoDir, $outputDir)
	{
		$this->subjectDir = $subjectDir;
		$this->repoDir    = $repoDir;
		$this->outputDir  = $outputDir;
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
		$this->runPdepend();
		$this->gatherFileClassMap();

		$logXml = simplexml_load_file($this->logFile);
		foreach ($logXml->package as $package) {
			foreach ($package->class as $class) {
				$nom = (int) $class['nom'];
				$wmc = (int) $class['wmc'];
				$ac = $nom ? $wmc / $nom : 0;
				$class = (string) $class['name'];

				$result->complexity($class, $ac);
			}
		}

		return $result;
	}

	private function runPdepend()
	{
		$this->logFile = $this->outputDir.'/pdepend.log';

		`pdepend --summary-xml={$this->logFile} {$this->subjectDir}`;
	}

	private function gatherFileClassMap()
	{
		$logXml = simplexml_load_file($this->logFile);
		$this->map = array();
		foreach ($logXml->package->class as $class) {
			$file = str_replace($this->repoDir.'/', '', $class->file['name']);
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