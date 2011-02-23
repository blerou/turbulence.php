<?php
/**
 * This file is part of chuggle.php
 *
 * @copyright Copyright (c) 2011 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php
 */

namespace paindriven\chuggle;

/**
 * Logger class
 *
 * @author blerou <sulik.szabolcs@gmail.com>
 */
class Logger
{
	/**
	 * log a message
	 *
	 * @param string $message the message
	 *
	 * @return void
	 */
	public function log($message)
	{
		echo sprintf('%s (%s)%s', $message, date('Y-m-d H:i:s'), PHP_EOL);
	}
}

?>