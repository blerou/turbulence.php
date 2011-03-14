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
	 * @var string the repository dir
	 */
	private $repoDir;

	/**
	 * Constructor
	 *
	 * @param string $repoDir the repository dir
	 */
	public function __construct($repoDir)
	{
		$this->repoDir = $repoDir;
	}

	/**
	 * determines the given dir contains a valid git repo
	 *
	 * @return bool
	 */
	public function isRepo()
	{
		$cwd = getcwd();
		chdir($this->repoDir);
		$out = `git status 2>&1`;
		chdir($cwd);

		return false === strpos($out, 'Not a git repository');
	}

	/**
	 * retrieve changes from repository
	 *
	 * @return array
	 */
	public function changes()
	{
		$cwd = getcwd();
		chdir($this->repoDir);
		$out = preg_split('/\\n/', `git log --all -M -C --numstat --format="%n"`);
		chdir($cwd);

		return $out;
	}
}

?>