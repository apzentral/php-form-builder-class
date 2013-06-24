<?php
namespace FBUILDER;

require_once(dirname(__FILE__).'/../FirePHPCore/FirePHP.class.php');

class FormField {
	private $name;
	private $type;
	private $description;
	private $validation;
	private $settings;
	private $values;
	private $cssclass;
	private $attributes;
	private $id;
	private $class;
	private $style;
	private $options;
	private $config;

	private $firephp;

	function __construct($init = array())
	{
		$this->firephp = \FirePHP::getInstance(true);

		// Override the internal variables
		//$this->firephp->log('=== Init ===');
		foreach($init as $k => $v)
		{
			$k = strtolower($k);
			$this->{$k} = $v;
		}

		$this->formatAttributes();
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	protected function formatAttributes()
	{
		foreach($this as $k => $v)
		{
			if(is_null($v) || $k === 'firephp')
			{
				continue;
			}
			//$this->firephp->log('=====');
			//$this->firephp->log($k);
			//$this->firephp->log($v);
		}
	}
}