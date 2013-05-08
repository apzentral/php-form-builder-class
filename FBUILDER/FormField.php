<?php
namespace FBUILDER;

class FormField {
	private $name;
	private $type;
	private $description;
	private $id;
	private $class;
	private $style;
	private $options;
	private $config;

	function __construct($init = array())
	{
		// Override the internal variables
		foreach($init as $k => $v)
		{
			$this->{$k} = $v;
		}
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
}