<?php

if (empty($_SERVER['argv'][1]) || empty($_SERVER['argv'][2])) {
  die(PHP_EOL.'Usage: php chuggle.php TARGET_DIR REPOSITORY_BASE_DIR'.PHP_EOL.PHP_EOL);
}

$targetDir = realpath($_SERVER['argv'][1]);
$repoDir   = realpath($_SERVER['argv'][2]).'/';

spl_autoload_register(function($class) {
	if (0 !== strpos($class, 'paindriven\\chuggle\\')) {
		return false;
	}

	$file = sprintf('%s/src/%s.php', __DIR__, str_replace('\\', '/', $class));
	if (file_exists($file)) {
		require $file;
		return true;
	}

	return false;
});

use paindriven\chuggle\Logger;
use paindriven\chuggle\Collector;
use paindriven\chuggle\ComplexityService;
use paindriven\chuggle\ChangesService;


$logger = new Logger();
$result = new Collector();

$complexity = new ComplexityService($logger, $targetDir, $repoDir);
$result     = $complexity->calculate($result);

$changes = new ChangesService($logger, $targetDir, $repoDir, $complexity->map());
$result  = $changes->calculate($result);

$logger->log('writing results...');
$outFile = get_temp_dir($targetDir).'/out.json';
$result->dumpJson($outFile);




function get_temp_dir($targetDir) {
  $tempDir   = '/tmp/'.preg_replace('/[^a-z0-9_-]/i', '_', $targetDir);
  if (!is_dir($tempDir)) {
    mkdir($tempDir);
  }

  return $tempDir;
}
