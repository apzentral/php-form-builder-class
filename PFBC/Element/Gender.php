<?php
namespace PFBC\Element;

class Gender extends Select {
	public function __construct($label, $name, array $properties = null) {
		$options = array(
			"" => "--Select Gender--",
			"M" => "Male",
			"F" => "Female",
			"T" => "Transgender",
			"U" => "Unknown",
		);
		parent::__construct($label, $name, $options, $properties);
    }
}
