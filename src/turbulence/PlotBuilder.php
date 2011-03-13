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
	public function buildViewer($outFile, $templateDir, $viewerFile)
	{
		$viewer = file_get_contents($templateDir.'/viewer.template.html');
		$viewer = strtr($viewer, array(
			'<*jquery.min.js*>'  => file_get_contents($templateDir.'/jquery.min.js'),
			'<*jquery.flot.js*>' => file_get_contents($templateDir.'/jquery.flot.js'),
			'<*json*>'           => file_get_contents($outFile),
		));
		file_put_contents($viewerFile, $viewer);
	}
}

?>