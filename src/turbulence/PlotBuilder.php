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
	public function buildViewer($outFile, $viewerFile)
	{
		$templateDir = __DIR__.'/template';
		$viewer      = file_get_contents($templateDir.'/viewer.template');
		$viewer      = strtr($viewer, array(
			'<*jq.js*>' => file_get_contents($templateDir.'/jq.js'),
			'<*json*>'  => file_get_contents($outFile),
		));
		file_put_contents($viewerFile, $viewer);
	}
}

?>