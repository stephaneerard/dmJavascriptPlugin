<?php

/*
 *
 *
 */

/**
 *
 * @author serard
 * @credit Apostrophe Framework a_js_call() helper
 *
 */
class dmJavascript implements dmJavascriptInterface
{

	/**
	 * @var sfEventDispatcher
	 */
	protected $dispatcher;

	/**
	 * @var boolean
	 */
	protected $listening_code 			= false;

	protected $_code								= array();

	/**
	 * @var boolean
	 */
	protected $listening_config			= false;

	protected $_config 							= array();


	/**
	 *
	 * @param sfEventDispatcher $dispatcher
	 */
	public function __construct(sfEventDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}


	/***********************************************************
	 *
	 * 												CODE
	 *
	 *
	 **********************************************************/

	/**
	 * @param string $callable
	 * @param array $args
	 * @return dmJavascript
	 */
	public function code($callable, $args)
	{
		$this->listenCode();
		$this->_code[] = array('callable' => $callable, 'args' => $args);
		return $this;
	}

	protected function listenCode()
	{
		if(!$this->listening_code)
		{
			$this->listening_code = true;
			$this->dispatcher->connect('dm.layout.filter_javascripts_codes', array($this, 'listenToLayoutFilterConfigEventForCode'));
		}
	}

	public function listenToLayoutFilterConfigEventForCode($event, $value)
	{
		$code = $this->processCode();
		$value = array_merge($value, $code);
		return $value;
	}

	protected function processCode()
	{
		$code = array();
		foreach($this->_code as $c)
		{
			$code[] = $this->parseJavascript($c['callable'], $c['args']);
		}
		
		return $code;
	}

	function parseJavascript($callable, $args)
	{
		$clauses = preg_split('/(\?)/', $callable, null, PREG_SPLIT_DELIM_CAPTURE);
		$code = '';
		$n = 0;
		$q = 0;
		foreach ($clauses as $clause)
		{
			if ($clause === '?')
			{
				$code .= json_encode($args[$n++]);
			}
			else
			{
				$code .= $clause;
			}
		}
		if ($n !== count($args))
		{
			throw new sfException('Number of arguments does not match number of ? placeholders in js call');
		}
		return $code;
	}

	/***********************************************************
	 *
	 * 												CONFIG
	 *
	 *
	 **********************************************************/

	/**
	 * @param unknown_type $callable
	 * @param unknown_type $args
	 */
	public function config($config)
	{
		$this->listenConfig();
		$this->_config($config);
		return $this;
	}

	protected function _config($config)
	{
		$this->_config = array_merge($this->_config, $config);
	}

	public function listenConfig()
	{
		if(!$this->listening_config)
		{
			$this->listening_config = true;
			$this->dispatcher->connect('layout.filter_config', array($this, 'listenToLayoutFilterConfigEventForConfig'));
		}
	}

	public function listenToLayoutFilterConfigEventForConfig($event, $value)
	{
		$value = array_merge($value, $this->_config);
		
		return $value;
	}
}