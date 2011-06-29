<?php

/**
 * 
 * @return dmJavascriptInterface
 */
function _get_dm_js()
{
	return dmContext::getInstance()->getServiceContainer()->getService('dm_javascript');
}

function dm_js_call()
{
	$args = func_get_args();
	
	$callable = $args[0];
	
	array_shift($args);
	
	return _get_dm_js()->code($callable, $args);
}

function dm_js_config($args)
{
	return _get_dm_js()->config($args);
}