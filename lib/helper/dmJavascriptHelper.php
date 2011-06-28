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
	$args = array_slice(func_get_args(), 1);
	
	return _get_dm_js()->code($callable, $args);
}

function dm_js_config()
{
	$args = array_slice(func_get_args(), 1);
	
	return _get_dm_js()->config($args);
	
}
