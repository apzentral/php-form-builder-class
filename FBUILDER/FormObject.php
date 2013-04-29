<?php
namespace FBUILDER;

class FormObject {
	private $name;
	private $title;
	private $language;
	private $fields;

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