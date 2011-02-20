<?php

if (empty($_SERVER['argv'][1]) || empty($_SERVER['argv'][2])) {
  die(PHP_EOL.'Usage: php chuggle.php TARGET_DIR REPOSITORY_BASE_DIR'.PHP_EOL.PHP_EOL);
}

$targetDir = realpath($_SERVER['argv'][1]);
$repoDir   = realpath($_SERVER['argv'][2]).'/';

$logger = new Logger();
$result = new Collector();

$comp   = new ComplexityServ($logger, $targetDir, $repoDir);
$comp->calc($result);
$map = $comp->map();

$ch = new ChangesServ($logger, $targetDir, $repoDir, $map);
$ch->changes($result);

$logger->log('writing results...');
$tempDir = get_temp_dir($targetDir);
$outFile = $tempDir.'/out.json';
$result->dump($outFile);




function get_temp_dir($targetDir) {
  $tempDir   = '/tmp/'.preg_replace('/[^a-z0-9_-]/i', '_', $targetDir);
  if (!is_dir($tempDir)) {
    mkdir($tempDir);
  }

  return $tempDir;
}

class ComplexityServ {
  function __construct(Logger $logger, $targetDir, $repoDir) {
    $this->logger    = $logger;
    $this->targetDir = $targetDir;
    $this->repoDir   = $repoDir;
  }

  function calc(Collector $result) {
    $this->logger->log('calculating complexity...');

    $this->logger->log('running PDepend...');
    $this->runPdepend();
    $this->logger->log('gathering file-class maps...');
    $this->gatherFileClassMap();

    $this->logger->log('gathering complexities...');
    $logXml = simplexml_load_file($this->logFile);
    foreach ($logXml->package->class as $class) {
      $nom   = (int)$class['nom'];
      $wmc   = (int)$class['wmc'];
      $ac    = $nom ? $wmc / $nom : 0;
      $class = (string)$class['name'];

      $result->complexity($class, $ac);
    }
  }

  private function runPdepend() {
    $this->logFile = get_temp_dir($this->targetDir).'/pdepend.log';
    
    exec(sprintf('pdepend --summary-xml=%s %s', $this->logFile, $this->targetDir));
  }

  private function gatherFileClassMap() {
    $logXml = simplexml_load_file($this->logFile);
    $this->map = array();
    foreach ($logXml->package->class as $class) {
      $file  = str_replace($this->repoDir, '', $class->file['name']);
      $class = (string) $class['name'];

      $this->map[$file] = $class;
    }
  }

  function map() {
    return $this->map;
  }
}

class ChangesServ {
  function __construct(Logger $logger, $targetDir, $repoDir, $map) {
    $this->logger    = $logger;
    $this->targetDir = $targetDir;
    $this->repoDir   = $repoDir;
    $this->map       = $map;
  }

  function changes(Collector $result) {
    $this->logger->log('calculating changes...');

    $this->logger->log('running git...');
    $out = $this->retrieveGitChanges($this->targetDir, $this->repoDir);

    $this->logger->log('splitting changes...');
    foreach ($out as $raw) {
      list($noc, $file) = explode(',', $raw);
      if (!isset($this->map[$file])) {
        continue;
      }
      $result->changes($this->map[$file], (int)$noc);
    }
  }

  private function retrieveGitChanges() {
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

class Collector {
  private $result = array();

  function complexity($class, $ac) {
    if (!isset($this->result[$class])) {
      $this->result[$class] = array();
    }
    $this->result[$class][0] = $ac;
  }

  function changes($class, $noc) {
    if (!isset($this->result[$class])) {
      $this->result[$class] = array();
    }
    $this->result[$class][1] = $noc;
  }

  function dump($file) {
    file_put_contents($file, json_encode($this->result));
  }
}

class Logger {
  function log($message) {
    echo sprintf('%s (%s)%s', $message, date('Y-m-d H:i:s'), PHP_EOL);
  }
}