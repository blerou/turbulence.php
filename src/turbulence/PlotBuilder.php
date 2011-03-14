<?php
/**
 * This file is part of turbulence.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace turbulence;

/**
 * PlotBuilder class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class PlotBuilder
{
	private $viewerFile;

	public function __construct($viewerFile)
	{
		$this->viewerFile = $viewerFile;
	}

	public function build($json)
	{
		$templateDir = __DIR__.'/template';
		$viewer      = file_get_contents($templateDir.'/viewer.template');
		$viewer      = strtr($viewer, array(
			'<*jq.js*>' => file_get_contents($templateDir.'/jq.js'),
			'<*json*>'  => $json,
		));
		file_put_contents($this->viewerFile, $viewer);
	}
}

?>