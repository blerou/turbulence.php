<?php
/**
 * This file is part of chuggle.php
 *
 * Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace paindriven\chuggle\scm;

/**
 * Git class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Git
{
	public function fetch($url, $targetDir)
	{
		if (is_dir($targetDir)) {
			`rm -fr {$targetDir}`;
		}
		`git clone {$url} {$targetDir}`;
	}

	public function changes($targetDir)
	{
		$cwd = getcwd();
		chdir($targetDir);
		$out = `git log --all -M -C --numstat --format="%n"`;
		chdir($cwd);

		return preg_split('/\\n/', $out);
	}
}

?>