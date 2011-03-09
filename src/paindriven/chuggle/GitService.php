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
 * GitService class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class GitService
{
	public function fetch($url, $targetDir)
	{
		if (is_dir($targetDir)) {
			`rm -fr {$targetDir}`;
		}
		`git clone {$url} {$targetDir}`;
	}
}

?>