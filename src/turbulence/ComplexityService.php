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
	 * Constructor
	 *
	 * @param string $subjectDir the target directory
	 * @param string $repoDir    the repository bsae
	 */
	public function __construct($subjectDir, $repoDir)
	{
		$this->subjectDir = $subjectDir;
		$this->repoDir    = $repoDir;
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
		$logFile = tempnam(sys_get_temp_dir(), 'pdepend_');

		`pdepend --summary-xml={$logFile} {$this->subjectDir}`;

		return simplexml_load_file($logFile);
	}
}

?>