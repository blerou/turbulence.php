<?php
/**
 * This file is part of turbulence.php
 *
 * Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence\scm;

/**
 * Git related commands adapter class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Git
{
	/**
	 * determines the given dir contains a valid git repo
	 *
	 * @param string $repoDir the repository dir
	 *
	 * @return bool
	 */
	public function isRepo($repoDir)
	{
		$cwd = getcwd();
		chdir($repoDir);
		$out = `git status 2>&1`;
		chdir($cwd);

		return false === strpos($out, 'Not a git repository');
	}

	/**
	 * retrieve changes from repository
	 *
	 * @param string $repoDir the repository dir
	 *
	 * @return array
	 */
	public function changes($repoDir)
	{
		$cwd = getcwd();
		chdir($repoDir);
		$out = preg_split('/\\n/', `git log --all -M -C --numstat --format="%n"`);
		chdir($cwd);

		return $out;
	}
}

?>