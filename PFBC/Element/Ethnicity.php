<?php
namespace PFBC\Element;

class Ethnicity extends Select {
	public function __construct($label, $name, array $properties = null) {
		$options = array(
			"" => "--Select Ethnicity--",
			"Hispanic" => "Hispanic",
			"Non-Hispanic" => "Non-Hispanic"
		);
		parent::__construct($label, $name, $options, $properties);
    }
}
