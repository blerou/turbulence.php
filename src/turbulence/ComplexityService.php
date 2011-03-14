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
		$logXml = $this->createPdependXml();

		foreach ($logXml->package as $package) {
			foreach ($package->class as $class) {
				$fileName  = str_replace($this->repoDir.'/', '', $class->file['name']);
				$className = (string) $class['name'];

				$result->classMap($fileName, $className);

				$nom = (int) $class['nom'];
				$wmc = (int) $class['wmc'];
				$ac  = $nom ? $wmc / $nom : 0;

				$result->averageMethodComplexity($className, $ac);

				foreach ($class->method as $method) {
					$result->lagestMethodComplexity($className, (int) $method['ccn']);
				}
			}
		}

		return $result;
	}

	private function createPdependXml()
	{
		$logFile = $this->outputDir.'/pdepend.log';

		`pdepend --summary-xml={$logFile} {$this->subjectDir}`;

		return simplexml_load_file($logFile);
	}
}

?>