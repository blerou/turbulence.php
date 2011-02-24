<?php

// source
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/../../src/'));
// test assets
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__.'/../'));

spl_autoload_register(function($class) {
	if (0 === strpos($class, 'paindriven\\chuggle'))
		require_once str_replace('\\', '/', $class).'.php';
});